<?php

namespace App\DataTables;

use App\Models\Customer;
use App\Enums\ModuleEnum;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Blade;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class CustomerDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->filterColumn('customer_div', function ($query, $keyword) {
                $query->where("name", 'like', '%' . $keyword . '%')
                    ->orWhere("customer_code", 'like', '%' . $keyword . '%')
                    ->orWhere("email", 'like', '%' . $keyword . '%');
            })
            ->addColumn('action', function (Customer $customer) {
                $html = "";

                $view = $delete = $lead = $edit = "";

                $user  = auth()->user();
                if($user->can(ModuleEnum::CUSTOMER_DETAIL->value))
                {
                    $view = '<li>
                            <button type="button"  data-modal-target="customer-detail-static-modal" data-modal-toggle="customer-detail-static-modal" data-customer-id="'.$customer->id.'" class="customer-viewBtn cursor-pointer block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">View</button>
                        </li>';
                }
                

                if($user->can(ModuleEnum::CUSTOMER_LEAD->value))
                {
                    $edit = '<li>
                            <a  href="'.route('customer.lead', $customer->id).'" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Lead</a>
                        </li>';
                }

                if($user->can(ModuleEnum::CUSTOMER_EDIT->value))
                {
                    $lead = '<li>
                            <a  href="'.route('customer.edit', $customer->id).'" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Edit</a>
                        </li>';
                }
                

                if($user->can(ModuleEnum::CUSTOMER_DELETE->value))
                {
                    $delete ='<li>
                                <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white del-button text-red-600" data-customer_id="'.$customer->id.'">Delete</a>
                            </li>';
                }
                


                $html = '<button id="dropdownLeftEndButton_'.$customer->id.'" data-dropdown-toggle="dropdownLeftEnd_'.$customer->id.'"  
                            class="inline-flex items-center p-2 text-sm font-medium text-center text-gray-900 bg-transparent rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none dark:text-white focus:ring-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-600 dropdown-trigger"
                            type="button">
                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                                <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                            </svg>
                    </button>

                    
                    <div id="dropdownLeftEnd_'.$customer->id.'" class="hidden text-white bg-white focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-2 py-2.5 text-left shadow-lg inline-flex items-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800 dropdown-menu">
                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownLeftEndButton_'.$customer->id.'">
                            '.$view.'
                            '.$lead.'
                            '.$edit.'
                            '.$delete.'
                        </ul>
                    </div>';

                return $html;
            })
            ->addColumn('customer_div', function (Customer $customer) {

                $active_inactive = $customer['status'] == 1 ? "bg-green-500" : "bg-red-500";

                $image = $customer["profile_image"] ? asset("storage/" . $customer["profile_image"]) : asset("user_profiles/user/user.png");
                $html = '<div
                                class="font-medium flex gap-2 p-1 items-center text-gray-900 whitespace-nowrap dark:text-white ">
                                <div class="relative me-4">
                                    <img class="w-10 h-10 rounded-full border-blue-800 border-2" src="'.$image.'" alt="profile image">
                                    <span class="top-0 start-7 absolute w-3.5 h-3.5 '.$active_inactive.' border-2 border-white dark:border-gray-800 rounded-full"></span>
                                </div>
                                <!--<img class="w-10 h-10 rounded-full" src="'.$image.'"
                                    alt="Rounded avatar">-->
                                <div class="">
                                    <p data-modal-target="static-modal" data-modal-toggle="static-modal" data-customer-id="'.$customer->id.'" class="customer-viewBtn cursor-pointer capitalize" title="'.htmlspecialchars($customer['name']).'">'.Str::words($customer['name'], 5, '...') . '</p>
                                    <p class="capitalize text-xs text-blue-800 ">#'.$customer['customer_code'] .'</p>
                                    <p class="text-gray-400">'. $customer['email'].'</p>
                                </div>
                            </div>';

                return $html;
            })
            ->addColumn('lead_create', function (Customer $customer) {
                
                return '<a href="'.route('customer.lead', $customer->id).'" class="text-center">
                                    <button type="button" title="Create Ticket"
                                        data-customer-id="{{ $lead->id }}"
                                        class="editBtn  text-white  bg-green-400 from-green-400 via-green-400 to-green-400 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-green-600  font-medium rounded-lg text-sm px-2 py-1 text-center  flex">
                                        Create Lead
                                    </button></a>';
            })
            ->addColumn('customer_type_div', function (Customer $customer) {
                
                $html = '<p class="text-gray-800 dark:text-white">'.ucfirst($customer['customer_type']).'</p>';
                if (isset($customer['company_reg_no'])){
                    $html .= '<p class="text-xs">REG. NO : '.$customer['company_reg_no'].'</p>';
                }

                return $html;
            })
            ->addColumn('status', function (Customer $customer) {
                if($customer['status'] == 0)
                {
                    return Blade::render('<x-badge type="danger" label="Inactive" class="" />');
                }
                return Blade::render('<x-badge type="success" label="Active" class="" />');
            })
            ->addColumn('authorized_person_count', function (Customer $customer) {
                return $customer->authorisedPersons->count();
            })
            ->addColumn('document_count', function (Customer $customer) {
                return $customer->customerDocs->count();
            })
            ->rawColumns(['action', 'status', 'customer_type_div', 'customer_div', 'lead_create'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Customer $model): QueryBuilder
    {
        return $model->orderBy('id', 'DESC')->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('customer-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                    ->orderBy(1)
                    ->selectStyleSingle()
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('pdf'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('customer_code'),
            Column::make('created_at'),
            Column::make('updated_at'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Customer_' . date('YmdHis');
    }
}
