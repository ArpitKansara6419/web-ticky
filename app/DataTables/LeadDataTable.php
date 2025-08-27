<?php

namespace App\DataTables;

use App\Models\Lead;
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
use Illuminate\Http\Request;

class LeadDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->filterColumn('lead_div', function ($query, $keyword) {
                $query->where("name", 'like', '%' . $keyword . '%')
                    ->orWhere("city", 'like', '%' . $keyword . '%')
                    ->orWhere("country", 'like', '%' . $keyword . '%')
                    ->orWhere("lead_code", 'like', '%' . $keyword . '%');
            })
            ->addColumn('action', function (Lead $lead) {
                $html = '';

                $detail = $edit = $delete = $permission = '';

                $user = auth()->user();
                if ($user->can(ModuleEnum::LEAD_DETAIL->value)) {
                    $detail =
                        '<li>
                        <a  href="#" data-lead-id="'.$lead->id.'" data-modal-target="static-modal" data-modal-toggle="static-modal" class="lead-viewBtn block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white editBtn">View</a>
                    </li>';
                }

                if (!isset($lead?->ticket?->ticket_code)){
                    $edit =
                        '<li>
                            <a href="'.route('lead.edit', $lead->id).'" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white" >Edit</a>
                        </li>';
                }

                if ($user->can(ModuleEnum::LEAD_DELETE->value) && !isset($lead?->ticket?->ticket_code)) {
                    $delete =
                        '<li>
                            <a href="#" data-lead_id="' . $lead->id . '" class="del-button block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white del-button text-red-600" >Delete</a>
                        </li>';
                }

                $clone = '<li>
                            <a href="'.route('lead.edit', $lead->id).'?clone=1" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white" >Clone</a>
                        </li>';


                $html =
                    '<button id="dropdownLeftEndButton_' .
                    $lead->id .
                    '" data-dropdown-toggle="dropdownLeftEnd_' .
                    $lead->id .
                    '"
                        class="inline-flex items-center p-2 text-sm font-medium text-center text-gray-900 bg-transparent rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none dark:text-white focus:ring-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-600 dropdown-trigger"
                        type="button">
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                            <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                        </svg>
                </button>

                
                <div id="dropdownLeftEnd_' .
                    $lead->id .
                    '" class="hidden text-white bg-white focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-2 py-2.5 text-left shadow-lg inline-flex items-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800 dropdown-menu">
                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownLeftEndButton_' .
                    $lead->id .
                    '">'.$clone.''.$edit.'' .$detail .'' .$delete .'
                    </ul>
                </div>';

                return $html;
                
            })
            ->addColumn('lead_type', function (Lead $lead){
                return $lead['lead_type'] ? ucfirst(str_replace('_', ' ', $lead['lead_type'])) : '-';
            })
            ->addColumn('lead_code', function (Lead $lead){
                return '#'.$lead['lead_code'];
            })
            ->addColumn('date_time_div', function (Lead $lead){
                $html = '<p class="leading-4">Date : '.$lead['task_start_date'] ?? '-'.'</p>';
                $html .= '<p>Time : '.$lead['task_time'].'</p>';
                if ($lead['lead_status'] == 'reschedule'){
                    $html .= '<p>Follow Up : '. Blade::render('<x-badge type="warning" label="'.$lead['reschedule_date'].'" class="" /> </p>');
                }
                $html .= Blade::render('<x-timezone timezone="'.e($lead->timezone).'"/>');
                return $html;
            })
            ->addColumn('location_div', function (Lead $lead){
                $html = '<span>'.$lead['city'].'</span><span> '.$lead['country'].'</span>';
                
                return $html;
            })
            ->addColumn('lead_status', function (Lead $lead){
                $html = "";
                if ($lead['lead_status'] == 'bid'){
                    $html .= Blade::render('<x-badge type="warning" label="Bid" class="" />');
                }else if ($lead['lead_status'] == 'confirm'){
                    $html .= Blade::render('<x-badge type="success" label="Confirm" class="" />');
                }
                else if ($lead['lead_status'] == 'reschedule'){
                    $html .= Blade::render('<x-badge type="info" label="Reschedule" class="" />');
                }else if ($lead['lead_status'] == 'cancelled'){
                    $html .= Blade::render('<x-badge type="danger" label="Cancelled" class="" />');
                }
                if (!$lead->is_ticket_created){
                    $html .= '<button class="lead-status-change-btn" type="button"
                                    data-lead-id="'.$lead['id'].'"
                                    data-lead-status="'.$lead['lead_status'].'"
                                    data-reschedule-date="'.$lead['reschedule_date'].'"
                                    data-modal-target="change-status-modal" data-modal-toggle="change-status-modal">
                                    Change
                                </button>';
                }

                return $html;
                
            })
            ->addColumn('customer_div', function (Lead $lead) {

                $active_inactive = $lead['customer']['status'] == 1 ? "bg-green-500" : "bg-red-500";

                $image = $lead["customer"]["profile_image"] ? asset("storage/" . $lead["customer"]["profile_image"]) : asset("user_profiles/user/user.png");
                $html = '<div
                                class="font-medium flex gap-2 p-1 items-center text-gray-900 whitespace-nowrap dark:text-white ">
                                <div class="relative me-4">
                                    <img class="w-10 h-10 rounded-full border-blue-800 border-2" src="'.$image.'" alt="profile image">
                                    <span class="top-0 start-7 absolute w-3.5 h-3.5 '.$active_inactive.' border-2 border-white dark:border-gray-800 rounded-full"></span>
                                </div>
                                <!--<img class="w-10 h-10 rounded-full" src="'.$image.'"
                                    alt="Rounded avatar">-->
                                <div class="">
                                    <p class="customer-viewBtn cursor-pointer capitalize" data-modal-target="customer-detail-static-modal" data-modal-toggle="customer-detail-static-modal" data-customer-id="'.$lead['customer']['id'].'" title="'.htmlspecialchars($lead['customer']['name']).'">'.Str::words($lead['customer']['name'], 2, '...') . '</p>
                                    <p class="capitalize text-xs text-blue-800 ">#'.$lead['customer']['customer_code'] .'</p>
                                    <!--<p class="text-gray-400">'. $lead['customer']['email'].'</p>-->
                                </div>
                            </div>';

                return $html;
            })
            ->addColumn('ticket_div', function(Lead $lead){
                if($lead->lead_status === 'confirm'){
                    if(isset($lead->ticket->id)){
                        return '<a href="'.route('ticket.show', $lead->ticket->id).'"
                                    class="text-decoration hover:dark:text-gray-300 hover:text-gray-800">
                                    '.$lead?->ticket?->ticket_code.'</a>';
                    }else{
                        return '<a href="'.route('ticket.create', [ 'lead_id' => $lead['id'], 'customer_id' => $lead['customer']['id'] ]).'" class="text-center">
                                    <button type="button" title="Create Ticket"
                                        data-customer-id="{{ $lead->id }}"
                                        class="editBtn  text-white bg-green-400 from-green-400 via-green-400 to-green-400 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-green-600  font-medium rounded-lg text-sm px-2 py-1 text-center  flex">
                                        Create Ticket
                                    </button></a>';
                    }
                }else{
                    return '-';
                }
                 
            })
            ->addColumn('lead_div', function(Lead $lead){
                
                $lead_type = $lead['lead_type'] ? ucfirst(str_replace('_', ' ', $lead['lead_type'])) : '-';
                $html = '<div
                    class="font-medium flex gap-2 p-1 items-center text-gray-900 whitespace-nowrap dark:text-white ">
                    
                    
                    <div class="">
                        <p class="capitalize cursor-pointer lead-viewBtn" data-lead-id="'.$lead->id.'" data-modal-target="static-modal" data-modal-toggle="static-modal" title="'.htmlspecialchars($lead['name']).'">'.Str::words($lead['name'], 3, '...') . '</p>
                        <p class="capitalize text-xs text-blue-800 ">#'.$lead['lead_code'] .'</p>
                        <p class="text-gray-400">'.$lead_type .'</p>
                    </div>
                </div>';

                return $html;
            })
            ->rawColumns(['action', 'status', 'lead_status', 'customer_div', 'date_time_div', 'location_div', 'ticket_div', 'lead_div'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Lead $model, Request $request): QueryBuilder
    {
        $result = $model->newQuery();
        
        if($request->customer_id){
            $result->where('customer_id', $request->customer_id);
        }

        $result->orderBy('id', 'DESC');
        
        return $result;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('lead-table')
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
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center'),
            Column::make('id'),
            Column::make('add your columns'),
            Column::make('created_at'),
            Column::make('updated_at'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Lead_' . date('YmdHis');
    }
}
