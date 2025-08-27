<?php

namespace App\Http\Controllers;

use App\DataTables\LeadDataTable;
use App\Models\Customer;
use App\Models\Lead;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $breadcrumbs = [
            ['name' => 'Home', 'url' => ""],
            ['name' => 'Leads', 'url' => "/lead"],
        ];

        // $leads = Lead::with('customer', 'ticket')->orderBy('id', 'desc')->get();

        return view('backend.leads.dataTable_list', [

            'breadcrumbs'   => $breadcrumbs

        ]);
    }

    /**
     * Lead Data Table
     *
     * @param LeadDataTable $leadDataTable
     * @return JsonResponse
     */
    public function dataTable(LeadDataTable $leadDataTable): JsonResponse
    {
        return $leadDataTable->ajax();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //if customer_id provided
        $breadcrumbs = [
            ['name' => 'Home', 'url' => "/lead"],
            ['name' => 'Leads', 'url' => "/lead"],
            ['name' => 'Create', 'url' => ""],
        ];

        $customer_id = $request['customer_id'] ?? null;

        $customers = Customer::all();


        return view('backend.leads.form', [
            'customers'     => $customers,
            'customer_id'   =>  $customer_id,
            'breadcrumbs' => $breadcrumbs,

        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name"               => "required",
            "id"                 => 'nullable|exists:leads,id',
            "customer_id"        => "required|integer|exists:customers,id",
            "lead_type"          => "required|in:full_time,short_term,dispatch_term",
            "task_start_date"    => "required|date",
            "task_end_date"      => "required|date",
            "reschedule_date"    => "nullable|date",
            "client_ticket_no"   => "nullable",
            "end_client_name"    => "nullable",
            "task_time"          => "required",
            "scope_of_work"      => "nullable|string",
            "currency_type"      => "required|in:dollar,euro,pound",
            "hourly_rate"        => "required|numeric",
            "half_day_rate"      => "required|numeric",
            "full_day_rate"      => "required|numeric",
            "monthly_rate"       => "nullable|numeric",
            "lead_status"        => "nullable|in:bid,confirm,reschedule,cancelled",
            "travel_cost"        => "nullable|numeric",
            "tool_cost"          => "nullable|numeric",
            'apartment'          => "nullable|string|max:70",
            'add_line_1'         => "nullable|string|max:255",
            'add_line_2'         => "nullable|string|max:255",
            'city'               => "nullable|string|max:50",
            "zipcode"            => "required|string|max:15",
            'country'            => "nullable|string|max:50",
            'latitude'            => "nullable",
            'longitude'            => "nullable",
            'timezone'             => 'required'
        ], [
            'task_start_date.before_or_equal' => 'The task start date cannot be after the task end date.',
        ]);

        // **Custom Validation Rule for Start Date**
        $validator->after(function ($validator) use ($request) {
            if ($request->task_start_date && $request->task_end_date) {
                $start_date = Carbon::parse($request->task_start_date);
                $end_date = Carbon::parse($request->task_end_date);

                if ($start_date->gt($end_date)) {
                    $validator->errors()->add('task_start_date', 'The task start date cannot be after the task end date.');
                }
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Proceed with storing the lead
        $validatedData = $validator->validated();
        $validatedData['task_start_date'] = Carbon::parse($validatedData['task_start_date'])->format('Y-m-d');
        $validatedData['task_end_date'] = Carbon::parse($validatedData['task_end_date'])->format('Y-m-d');
        $validatedData['task_time'] = Carbon::parse($validatedData['task_time'])->format('H:i:s');

        if ($request->reschedule_date) {
            $validatedData['reschedule_date'] = Carbon::parse($request->reschedule_date)->format('Y-m-d');
        }

        if (empty($request['id'])) {
            $lastLead = Lead::latest('id')->first();
            $lastCode = $lastLead ? (int)str_replace('AIM-L-', '', $lastLead->lead_code) : 99;
            $nextCode = $lastCode + 1;
            $validatedData['lead_code'] = 'AIM-L-' . $nextCode;
        }

        DB::beginTransaction();
        $lead = Lead::updateOrCreate(['id' => $request['id']], $validatedData);
        DB::commit();

        $message = $request['id'] ? 'Lead updated successfully' : 'New Lead added successfully.';

        return redirect()->route('lead.index')->with('toast', [
            'type'      => 'success',
            'message'   =>  $message
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Lead $lead)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, Request $request)
    {
        try {

            $lead = Lead::with('customer')->findOrFail($id);
            $customers = Customer::all();

            $breadcrumbs = [
                ['name' => 'Home', 'url' => "/lead"],
                ['name' => 'Leads', 'url' => "/lead"],
                ['name' => 'Edit', 'url' => ""],
            ];

            $clone = null;
            if ($request->get('clone', null)) {
                $clone = true;
            }

            return view('backend/leads/form', [

                'lead'        => $lead,
                'breadcrumbs' => $breadcrumbs,
                'customers'   => $customers,
                'clone'       => $clone

            ])->with('toast', [

                'type' => 'success',
                'message' => 'Lead data fetched successfully'
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
    public function update(Request $request, Lead $lead)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        /// dd($id);
        try {

            Lead::findOrFail($id)->delete();

            // session()->flash('toast', [
            //     'type'    => 'success',
            //     'message' => 'Lead Removed Successfully.'
            // ]);
            return response()->json([
                'status' => true,
                'message' => 'Lead Removed Successfully.'
            ]);

            // return redirect()->route('lead.index');
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.'
            ]);
            // session()->flash('toast', [
            //     'type'    => 'danger',
            //     'message' => 'Something went wrong, Please Try Again.',
            //     'error'   => $e->getMessage()
            // ]);
            // return redirect()->route('lead.index');
        }
    }

    public function ChangeLeadStatus(Request $request)
    {

        try {

            $updateData['lead_status'] = $request->lead_status;

            if ($request->reschedule_date) {
                $updateData['reschedule_date'] = Carbon::parse($request->reschedule_date)->format('Y-m-d');
            }

            Lead::findOrFail($request->lead_id)->update($updateData);

            session()->flash('toast', [
                'type'    => 'success',
                'message' => 'Lead Status updated Successfully.'
            ]);

            return redirect()->route('lead.index');
        } catch (\Exception $e) {
            session()->flash('toast', [
                'type'    => 'danger',
                'message' => 'Something went wrong, Please Try Again.',
                'error'   => $e->getMessage()
            ]);
            return redirect()->route('lead.index');
        }
    }

    public function getLeadDetails(string $id)
    {
        $leadData = Lead::findorFail($id);

        return response()->json([
            'status' => 'success',
            'leadData' => $leadData,
        ], 200);
    }
}
