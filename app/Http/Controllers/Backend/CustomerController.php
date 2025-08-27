<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\CustomerDataTable;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerAuthorisedPerson;
use App\Models\CustomerDocument;
use App\Models\CustomerPayable;
use App\Models\CustomerPayout;
use App\Models\Lead;
use App\Models\Ticket;
use App\Models\TicketWork;
use App\Models\User;
use App\Repositories\Interface\CustomerAuthorisedPersonRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use function Termwind\render;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    public $customerAuthorisedPeople;

    public function __construct(
        CustomerAuthorisedPersonRepositoryInterface $customerAuthorisedPeople
    )
    {
        $this->customerAuthorisedPeople = $customerAuthorisedPeople;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $breadcrumbs = [
            ['name' => 'Home', 'url' => ""],
            ['name' => 'Customers', 'url' => "/customer"],
        ];

        $user = null;
        $customers = Customer::with('customerDocs')->orderBy('created_at', 'desc')->get();

        return view('backend.customer.dataTable_list', ['breadcrumbs' => $breadcrumbs, 'user' => $user]);

        // return view('backend.customer.index', ['customers' => $customers, 'breadcrumbs' => $breadcrumbs, 'user' => $user]);
    }

    /**
     * AJAX data
     *
     * @param CustomerDataTable $customerDataTable
     * @return JsonResponse
     */
    public function ajaxData(CustomerDataTable $customerDataTable) : JsonResponse
    {
        return $customerDataTable->ajax();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $breadcrumbs = [
            ['name' => 'Home', 'url' => "/customer"],
            ['name' => 'Customers', 'url' => "/customer"],
            ['name' => 'Create', 'url' => ""],
        ];
        return view('backend.customer.form', ['breadcrumbs' => $breadcrumbs]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'id'                => 'nullable|integer',
                'customer_type'     => 'required|string|in:company,freelancer',
                'name'              => 'required|string|max:100',
                'email'             => 'required|email|unique:customers,email,' . $request->id,
                'company_reg_no'    => 'nullable',
                'vat_no'            => 'nullable',
                'address'           => 'nullable|string|max:200',
                // 'auth_person.*.name'       => [
                //     'required|max:60'
                // ],
                // 'auth_person_email.*.email' => $request['customer_type'] == 'company' ? 'required|email|max:60' : 'nullable',
                // 'auth_person_contact.*.contact' => 'required|max:15',
                'profile_image'     => 'nullable|file|mimes:jpg,jpeg,png',
                'status'            => 'required|in:0,1',
                'document'          => 'nullable|array',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Get only email-related validation errors
            $emailErrors = collect([
                'email' => $e->validator->errors()->get('email'),
                'auth_person_email' => $e->validator->errors()->get('auth_person_email'),
            ])->flatten()->filter();


            if ($emailErrors->isNotEmpty()) {
                session()->flash('toast', [
                    'type' => 'danger',
                    'message' => 'Validation Error!',
                    'error' => $emailErrors->implode(' '),
                ]);

                return redirect()->back()->withInput();
            }

            throw $e; // Throw other validation errors normally
        }


        if (empty($request['id'])) {
            $lastCustomer = Customer::latest('id')->first();
            $lastCode = $lastCustomer
                ? (int) str_replace('AIM-C-', '', $lastCustomer->customer_code)
                : 99;
            $validatedData['customer_code'] = 'AIM-C-' . ($lastCode + 1);
        }

        // Handle profile image upload
        $fileName = $request['id'] ? Customer::findOrFail($request['id'])->profile_image : null;
        if ($request->hasFile('profile_image') && $request->file('profile_image')->isValid()) {
            $file = $request->file('profile_image');
            $path = $file->store('profiles', 'public');
            chmod(storage_path('app/public/' . $path), 0644);
            $validatedData['profile_image'] = $path;
        }

        DB::beginTransaction();

        try {
            $customer = Customer::updateOrCreate(
                ['id' => $request['id']],
                $validatedData
            );
            CustomerAuthorisedPerson::where('customer_id', $customer->id)->delete();
                
            foreach($request->auth_person as $key => $value)
            {
                $data = [
                    'customer_id' => $customer->id,
                    'person_name' => $request->auth_person[$key],
                    'person_email' => $request->auth_person_email[$key],
                    'person_contact_number' => $request->auth_person_contact[$key],
                ];
                CustomerAuthorisedPerson::create($data);
            }

            if (!empty($request->file('document'))) {
                $documentInput = $request->input('document');
                foreach ($request->file('document') as $key => $doc) {
                    if (isset($doc['doc_name']) && $doc['doc_name']->isValid()) {
                        $path = $doc['doc_name']->store('customer_documents', 'public');
                        $docExp = Carbon::parse($documentInput[$key]['doc_exp_date'])->format('Y-m-d');
                        CustomerDocument::create([
                            'title'       => $documentInput[$key]['title'],
                            'customer_id' => $customer['id'],
                            'doc'         => $path,
                            'doc_expiry'  => $docExp,
                        ]);
                    }
                }
            }

            DB::commit();

            session()->flash('toast', [
                'type' => 'success',
                'message' => $request['id'] ? 'Customer details updated successfully' : 'Customer added successfully.',
            ]);

            return redirect()->route('customer.index');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error while storing user', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString()
            ]);

            session()->flash('toast', [
                'type'    => 'danger',
                'message' => 'Failed to save user. Please try again',
                'error'   => $e->getMessage(),
            ]);

            return redirect()->back()->withInput();
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        try {

            $customer = Customer::with(['customerDocs', 'authorisedPersons'])->findOrFail($id);


            $breadcrumbs = [
                ['name' => 'Home', 'url' => "/customer"],
                ['name' => 'Customers', 'url' => "/customer"],
                ['name' => 'Edit', 'url' => ""],
            ];
            return view('backend/customer/form', [
                'customer' => $customer,
                'breadcrumbs' => $breadcrumbs
            ])->with('toast', [
                'type' => 'success',
                'message' => 'Customer data fetched successfully'
            ]);
        } catch (\Exception $e) {

            Log::error('Error fetching customer details for ID ' . $id . ': ' . $e->getMessage());

            return back()->with('toast', [
                'type' => 'warning',
                'message' => 'Something went wrong while fetching data, try again'
            ]);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {}

    public function destroy(string $id)
    {
        try {
            Customer::findOrFail($id)->delete();
            CustomerPayout::where('customer_id', $id)->delete();
            Lead::where('customer_id', $id)->delete();
            $customerTicketIds = $customerTicketIds = Ticket::where('customer_id', $id)->pluck('id')->toArray();
            TicketWork::whereIn('ticket_id', $customerTicketIds)->delete();
            Ticket::where('customer_id', $id)->delete();
            // session()->flash('toast', [
            //     'type'    => 'success',
            //     'message' => 'Customer Removed Successfully.'
            // ]);

            // return redirect()->route('customer.index');

            return response()->json([
                'status' => true,
                'message' => 'Customer Removed Successfully.'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong, Please Try Again.',
            ]);

            // session()->flash('toast', [
            //     'type'    => 'danger',
            //     'message' => 'Something went wrong, Please Try Again.',
            //     'error'   => $e->getMessage()
            // ]);

            // return redirect()->route('customer.index');
        }
    }

    /**
     * Remove the specific document  of customer 
     */
    public function deleteDoc(Request $request)
    {
        $docId = $request['doc_id'];

        $doc = CustomerDocument::findOrFail($docId);

        $authUser = Auth::user();

        // Check if the user is either an admin or owns the document
        if ($authUser->hasRole('admin') || $doc->customer_id === $authUser->id) {

            $doc->delete();
            return response()->json(['success' => true, 'message' => 'Document deleted successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
    }

    /**
     * Get the customer specific lead list
     */
    public function getLeadList(string $id)
    {

        // $leads = Customer::with('leads')->findOrFail($id);
        // dd($leads->toArray());
        $breadcrumbs = [
            ['name' => 'Home', 'url' => "/customer"],
            ['name' => 'Customers', 'url' => "/customer"],
            ['name' => 'Leads', 'url' => ""],
        ];

        $customer = Customer::find($id);

        return view('backend.customer.lead-index', [
            // 'leads'       => $leads,
            'breadcrumbs' => $breadcrumbs,
            'customer' => $customer
        ]);
    }

    public function clientInvoice(string $id)
    {
        // Find the CustomerPayout or return 404 if not found
        $customerPayout = CustomerPayout::with('customer')->find($id);

        if (!$customerPayout) {
            abort(404);
        }


        $ticketWorkIds = $customerPayout->customer_payable_ids;

        if (empty($ticketWorkIds) || !is_array($ticketWorkIds)) {
            return response()->json([
                'success' => false,
                'message' => 'No customer payable IDs found.',
            ], 400);
        }


        // Get ticket works related to the EngineerPayout

        $payables = CustomerPayable::with('engineer', 'ticket')
            ->whereIn('id', $ticketWorkIds)
            ->get();

        return view('backend.customer.client-invoice', compact('customerPayout', 'payables'));
    }

    public function clientInvoiceTest(Request $request)
    {
        return view('backend/customer/client-invoice_test');
    }

    public function clientBreakup(string $id)
    {
        // Find the CustomerPayout or return 404 if not found
        $customerPayout = CustomerPayout::with('customer')->find($id);

        if (!$customerPayout) {
            abort(404);
        }


        $ticketWorkIds = $customerPayout->customer_payable_ids;

        if (empty($ticketWorkIds) || !is_array($ticketWorkIds)) {
            return response()->json([
                'success' => false,
                'message' => 'No customer payable IDs found.',
            ], 400);
        }


        // Get ticket works related to the EngineerPayout

        $payables = CustomerPayable::with('engineer', 'ticket', 'ticket.lead')
            ->whereIn('id', $ticketWorkIds)
            ->get();

        return view('backend/customer/client-breakup', compact('customerPayout', 'payables'));
    }

    public function clientBreakupTest(Request $request)
    {
        return view('backend/customer/client-breakup_test');
    }

    public function getCustomerDetails(string $id)
    {
        $customer = Customer::with(['customerDocs', 'authorisedPersons'])->findorFail($id);

        return response()->json([
            'status' => 'success',
            'customer' => $customer,
        ], 200);
    }
}
