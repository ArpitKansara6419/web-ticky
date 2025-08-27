<?php

namespace App\DataTables;

use App\Models\Ticket;
use App\Enums\ModuleEnum;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Blade;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class TicketDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (Ticket $ticket) {
                $html = "";

                $view = $delete = $edit = "";

                $user  = auth()->user();
                if($user->can(ModuleEnum::TICKET_DETAIL->value))
                {
                    $view = '<li>
                            <a href="'.route('ticket.show', $ticket->id).'" type="button"   class=" cursor-pointer block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">View</a>
                        </li>';
                }
                

                if($user->can(ModuleEnum::TICKET_EDIT->value) && $ticket['status'] != 'close')
                {
                    $edit = '<li>
                            <a  href="'.route('ticket.edit', $ticket->id).'" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Edit</a>
                        </li>';
                }

                
                

                if($user->can(ModuleEnum::TICKET_DELETE->value))
                {
                    $delete ='<li>
                                <a data-ticket-id="'.$ticket->id.'" href="#" class="deleteBtn block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white del-button text-red-600">Delete</a>
                            </li>';
                }
                
                $rand = rand(100000,999999);

                $html = '<button id="dropdownLeftEndButton_'.$ticket->id.$rand.'" data-dropdown-toggle="dropdownLeftEnd_'.$ticket->id.$rand.'"  
                            class="inline-flex items-center p-2 text-sm font-medium text-center text-gray-900 bg-transparent rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none dark:text-white focus:ring-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-600 dropdown-trigger"
                            type="button">
                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                                <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                            </svg>
                    </button>

                    
                    <div id="dropdownLeftEnd_'.$ticket->id.$rand.'" class="hidden text-white bg-white focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-2 py-2.5 text-left shadow-lg inline-flex items-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800 dropdown-menu">
                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownLeftEndButton_'.$ticket->id.$rand.'">
                            '.$view.'
                            '.$edit.'
                            '.$delete.'
                        </ul>
                    </div>';

                return $html;
            })
            ->addColumn('ticket_code', function(Ticket $ticket){
                return "#".$ticket->ticket_code;
            })
            ->addColumn('customer_div', function (Ticket $ticket) {

                $active_inactive = $ticket['customer']['status'] == 1 ? "bg-green-500" : "bg-red-500";

                $image = $ticket["customer"]["profile_image"] ? asset("storage/" . $ticket["customer"]["profile_image"]) : asset("user_profiles/user/user.png");
                $html = '<div
                                class="font-medium flex gap-2 p-1 items-center text-gray-900 whitespace-nowrap dark:text-white ">
                                <!--<div class="relative me-4">
                                    <img class="w-10 h-10 rounded-full border-blue-800 border-2" src="'.$image.'" alt="profile image">
                                    <span class="top-0 start-7 absolute w-3.5 h-3.5 '.$active_inactive.' border-2 border-white dark:border-gray-800 rounded-full"></span>
                                </div>-->
                                <!--<img class="w-10 h-10 rounded-full" src="'.$image.'"
                                    alt="Rounded avatar">-->
                                <div class="">
                                    <p class="customer-viewBtn cursor-pointer capitalize" data-modal-target="customer-detail-static-modal" data-modal-toggle="customer-detail-static-modal" data-customer-id="'.$ticket['customer']['id'].'" title="'.htmlspecialchars($ticket['customer']['name']).'">'.Str::words($ticket['customer']['name'], 2, '...') . '</p>
                                    <p class="capitalize text-xs text-blue-800 ">#'.$ticket['customer']['customer_code'] .'</p>
                                    <!--<p class="text-gray-400">'. $ticket['customer']['email'].'</p>-->
                                </div>
                            </div>';

                return $html;
            })
            ->addColumn('lead_and_task', function(Ticket $ticket){
                $html = '<div
                                class="font-medium flex gap-2 p-1 items-center text-gray-900 whitespace-nowrap dark:text-white ">
                                <div class="">
                                    <a href="'.route('ticket.show', $ticket->id).'" title="'.$ticket['task_name'].'">
                                        <p class="capitalize">'.Str::words($ticket['task_name'], 2, '...') . '</p>
                                    </a>
                                    <p class="capitalize text-xs text-blue-800 ">#'.$ticket['ticket_code'].'</p>
                                </div>
                            </div>';
                return $html;
            })
            ->addColumn('location_div', function (Ticket $ticket){
                $html = '<span>'.$ticket['city'].'</span><span> '.$ticket['country'].'</span>';
                
                return $html;
            })
            ->addColumn('date_and_time_div', function (Ticket $ticket) {

                $html = "";

                $html .= Blade::render('<x-timezone timezone="'.e($ticket->timezone).'"/>');
                if($ticket['task_start_date'] != $ticket['task_end_date']){
                    $html .= '<p class="text-sm text-gray-500 whitespace-nowrap">
                                        Start : '.$ticket['ticket_start_date_tz'].'
                                    </p>
                                    <p class="text-sm text-gray-500 whitespace-nowrap">
                                        End &nbsp; : '.$ticket['ticket_end_date_tz'].'
                                    </p>';
                }else{
                    $html .= '<p class="text-sm text-gray-500 whitespace-nowrap">Date : '.$ticket['ticket_start_date_tz'].'</p>';
                }

                $html .= '<p class="text-sm text-gray-500 whitespace-nowrap">Time : '.$ticket['ticket_time_tz'].'</p>';
                               
                return $html;
                                
            })
            ->addColumn('status_div', function (Ticket $ticket){
                if ($ticket['status'] === 'inprogress'){
                    return Blade::render('<x-badge type="inprogress" label="In Progress" class="" />');
                }elseif($ticket['status'] === 'hold'){
                    return Blade::render('<x-badge type="hold" label="On Hold" class="" />');
                }elseif($ticket['status'] === 'break'){
                    return Blade::render('<x-badge type="break" label="On Break" class="" />');
                }elseif($ticket['status'] === 'close'){
                    return Blade::render('<x-badge type="close" label="Close" class="" />');
                }elseif($ticket['status'] === 'expired'){
                    return Blade::render('<x-badge type="expired" label="Expired" class="" />');
                }
                else{
                    if($ticket['engineer_status'] == 'offered'){
                        return Blade::render('<x-badge type="offered" label="Offered" class="" />');
                    }elseif($ticket['engineer_status'] == 'accepted' && date('Y-m-d') == $ticket['task_start_date']){
                        return Blade::render('<x-badge type="expired" label="Not Started" class="" />');
                    }elseif($ticket['engineer_status'] == 'accepted'){
                        return Blade::render('<x-badge type="expired" label="Accepted" class="" />');
                    }
                    
                }
            })
            ->addColumn('engineer_assigned_div', function (Ticket $ticket){
                $html = "";

                $image = isset($ticket['engineer']['profile_image']) ? asset('storage/' . $ticket['engineer']['profile_image']) : asset('user_profiles/user/user.png');

                if (isset($ticket['engineer']) && !empty($ticket['engineer'])){
                    $html .= '<div
                                    class="font-medium flex gap-2 p-1 items-center text-gray-900 whitespace-nowrap dark:text-white ">
                                    <img class="w-10 h-10 rounded-full border"
                                        src="'.$image.'"
                                        alt="Rounded avatar">
                                    <div class="">
                                        <a  href="'.route('engg.show', $ticket->engineer->id).'" target="_blank">
                                            <p class="capitalize leading-4">'.$ticket['engineer']['first_name'].'
                                                '.$ticket['engineer']['last_name'].'
                                            </p>
                                        </a>
                                        <p class="text-gray-400 truncate w-36"
                                            title="'.$ticket['engineer']['email'].'">
                                            '.$ticket['engineer']['email'].'
                                        </p>
                                    </div>';
                        if ( ($ticket['status'] === 'inprogress' || $ticket['status'] === 'hold' || $ticket['status'] === 'close' || $ticket['status'] === 'expired' )){

                        }else{
                            $html .= '<button class="engineer-change-btn mr-3" type="button"
                                        data-ticket-id="'.$ticket['id'].'"
                                        data-modal-target="change-enginner-model"
                                        data-modal-toggle="change-enginner-model">
                                        Change
                                    </button>';
                        }

                    

                    $html .= '</div>';
                }else{
                    $html .= '<div class="flex flex-col justify-center items-center gap-3">
                                    <span>Not Assign</span>
                                    <button class="engineer-change-btn text-green-300 hover:text-green-500" type="button"
                                        data-ticket-id="'.$ticket['id'].'"
                                        data-modal-target="change-enginner-model"
                                        data-modal-toggle="change-enginner-model">
                                        Assign
                                    </button>
                                </div>';
                }

                return $html;

                
            })
            ->filterColumn('lead_and_task', function ($query, $keyword) {
                $query->where("ticket_code", 'like', '%' . $keyword . '%')
                        ->orWhere("task_name", 'like', '%' . $keyword . '%');
                // $query->orWhere("city", 'like', '%' . $keyword . '%')
                //     ->orWhere("country", 'like', '%' . $keyword . '%');
                // $query->whereHas("lead", function ($q) use ($keyword) {
                //     $q->where("name", 'like', '%' . $keyword . '%')
                //         ->orWhere("lead_code", 'like', '%' . $keyword . '%')
                //         ->orWhere("task_name", 'like', '%' . $keyword . '%');
                // });
            })

            ->filterColumn('location_div', function ($query, $keyword) {
                $query->where("city", 'like', '%' . $keyword . '%')
                        ->orWhere("country", 'like', '%' . $keyword . '%');
            })
            // ->filterColumn('ticket_code', function ($query, $keyword) {
            //     $query->where("ticket_code", 'like', '%' . $keyword . '%')
            //         ->orWhere("city", 'like', '%' . $keyword . '%')
            //         ->orWhere("country", 'like', '%' . $keyword . '%');
            // })
            ->filterColumn('customer_div', function ($query, $keyword) {
                $query->whereHas("customer", function ($q) use ($keyword) {
                    $q->where("name", 'like', '%' . $keyword . '%')
                         ->orWhere("customer_code", 'like', '%' . $keyword . '%');
                });
            })
            ->filterColumn('engineer_assigned_div', function ($query, $keyword) {
                $query->whereHas("engineer", function ($q) use ($keyword) {
                    $q->where("first_name", 'like', '%' . $keyword . '%')
                        ->orWhere("last_name", 'like', '%' . $keyword . '%')
                        ->orWhere("engineer_code", 'like', '%' . $keyword . '%');
                });
            })
            ->filterColumn('date_and_time_div', function ($query, $keyword) {
                $query->where("task_start_date", 'like', '%' . $keyword . '%')
                        ->orWhere("task_end_date", 'like', '%' . $keyword . '%')
                        ->orWhere("timezone", 'like', '%' . $keyword . '%');
            })
            ->rawColumns(['action', 'status_div', 'date_and_time_div', 'lead_and_task', 'customer_div', 'engineer_assigned_div', 'location_div'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Ticket $model, Request $request): QueryBuilder
    {
        $result = $model->newQuery();
        if($request->status == 'Offered'){
            $result->where('engineer_status', 'Offered');
        }else if($request->status == 'inprogress'){
            $result->where('status', 'inprogress');
        }else if($request->status == 'onhold'){
            $result->where('status', 'hold');
        }else if($request->status == 'close'){
            $result->where('status', 'close');
        }else if($request->status == 'expired'){
            $result->where('status', 'expired');
        }else if($request->status == 'accepted'){
            $result->where('engineer_status', 'accepted');
            $result->whereNull('status');
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
                    ->setTableId('ticket-table')
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
        return 'Ticket_' . date('YmdHis');
    }
}
