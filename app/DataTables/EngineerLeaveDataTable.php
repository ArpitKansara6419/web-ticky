<?php

namespace App\DataTables;

use App\Models\EngineerLeave;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class EngineerLeaveDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function(EngineerLeave $engineer_leave){
                $html = "";

                $accept = $reject = "";
                
                $accept = '<li>
                        <a  href="#" data-href="'.route('leave.approve', $engineer_leave->id).'"  data-engineer_leave="' . $engineer_leave->id . '" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white accept_btn">Accept</a>
                    </li>';
                
                

                $reject = '<li>
                    <a  href="#"  data-href="'.route('leave.reject', $engineer_leave->id).'" data-engineer_leave="' . $engineer_leave->id . '" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white text-red-800 reject_btn">Reject</a>
                </li>';
                


                $html = '<button id="dropdownLeftEndButton_'.$engineer_leave->id.'" data-dropdown-toggle="dropdownLeftEnd_'.$engineer_leave->id.'"  
                            class="inline-flex items-center p-2 text-sm font-medium text-center text-gray-900 bg-transparent rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none dark:text-white focus:ring-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-600 dropdown-trigger"
                            type="button">
                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                                <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                            </svg>
                    </button>

                    
                    <div id="dropdownLeftEnd_'.$engineer_leave->id.'" class="hidden text-white bg-white focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-2 py-2.5 text-left shadow-lg inline-flex items-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800 dropdown-menu">
                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownLeftEndButton_'.$engineer_leave->id.'">
                            '.$accept.'
                            '.$reject.'
                        </ul>
                    </div>';

                return $html;
            })
            ->addColumn('engineer_name', function(EngineerLeave $engineer_leave){
                return $engineer_leave->engineer?->first_name. ' ' . $engineer_leave->engineer?->last_name;
            })
            ->addColumn('leave_date_div', function(EngineerLeave $engineer_leave){
                $from = Carbon::parse($engineer_leave->paid_from_date ?? $engineer_leave->unpaid_from_date)->format('d F');
                $to = Carbon::parse($engineer_leave->unpaid_to_date ?? $engineer_leave->paid_to_date)->format('d F');

                return $from . ' TO ' . $to;
            })
            ->addColumn('days_div', function(EngineerLeave $engineer_leave){
                return (int)($engineer_leave->paid_leave_days ?? 0 + $engineer_leave->unpaid_leave_days ?? 0);
            })
            ->addColumn('type_div', function(EngineerLeave $engineer_leave){
                if (!empty($engineer_leave->paid_from_date)){
                    $paid  = $engineer_leave->paid_from_date ? 'Paid' : '';
                    return '<div
                        class="bg-green-100 text-green-500 border  w-fit py-[.15rem] px-[.4rem] rounded-lg inline-block">
                        '.$paid.'
                    </div>';
                }
                if (!empty($engineer_leave->unpaid_from_date)){
                    $unpaid =  $engineer_leave->unpaid_from_date ? 'Unpaid' : '';
                    return '<div
                        class="mt-2 bg-red-100 text-red-500 border  w-fit py-[.15rem] px-[.4rem] rounded-lg inline-block">
                        '.$unpaid.'
                    </div>';
                }
            })
            ->addColumn('document_div', function(EngineerLeave $engineer_leave){
                if($engineer_leave->leave_approve_status === 'pending' || $engineer_leave->leave_approve_status === 'reject')
                {
                    if (!empty($engineer_leave->unsigned_paid_document)){
                        return '<a title="Paid Document"
                                                        href="'.asset('storage/'.$engineer_leave->unsigned_paid_document).'"
                                                        target="_blank">
                                                        <div class="flex items-center gap-3">
                                                            <img src="'.asset("assets/pdf-icon.png").'" class="w-8 h-8" alt="">
                                                        </div>
                                                    </a>';
                    }
                    if (!empty($engineer_leave->unsigned_unpaid_document)){
                        return '<a title="Unpaid Document"
                                    href="'.asset('storage/'.$engineer_leave->unsigned_unpaid_document).'"
                                    target="_blank">
                                    <div class="flex items-center gap-3">
                                        <img src="'.asset('assets/pdf-icon.png') .'" class="w-8 h-8" alt="">
                                    </div>
                                </a>';
                    }else{
                        return ' <p>
                                -
                            </p>';
                    }
                }else if($engineer_leave->leave_approve_status === 'approved')
                {
                    if (!empty($engineer_leave->signed_paid_document)){
                        return '<a title="Paid Document"
                                                        href="'.asset('storage/'.$engineer_leave->signed_paid_document).'"
                                                        target="_blank">
                                                        <div class="flex items-center gap-3">
                                                            <img src="'.asset("assets/pdf-icon.png").'" class="w-8 h-8" alt="">
                                                        </div>
                                                    </a>';
                    }
                    if (!empty($engineer_leave->signed_unpaid_document)){
                        return '<a title="Unpaid Document"
                                    href="'.asset('storage/'.$engineer_leave->signed_unpaid_document).'
                                    target="_blank">
                                    <div class="flex items-center gap-3">
                                        <img src="'.asset('assets/pdf-icon.png') .'
                                        class="w-8 h-8" alt="">
                                    </div>
                                </a>';
                    }else{
                        return ' <p>
                                -
                            </p>';
                    }
                }
                
            })
            ->addColumn('status_div', function(EngineerLeave $engineer_leave){
                if($engineer_leave->leave_approve_status === 'pending')
                {
                    return '<div
                            class="bg-green-100 text-green-500 border border-green-500 w-fit py-[.15rem] px-[.4rem] rounded-lg inline-block">
                            '.ucfirst($engineer_leave->leave_approve_status).'
                        </div>';
                }else if($engineer_leave->leave_approve_status === 'approved'){
                    $class = $engineer_leave->leave_approve_status === 'approved' ? ' text-green-500' : 'bg-yellow-100 text-yellow-500';
                    return '<div
                                    class="w-fit px-1.5 py-1 rounded-lg '.$class.'
                                            ">
                                    <svg class="w-5 h-5 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="2"
                                            d="M5 11.917 9.724 16.5 19 7.5" />
                                    </svg>
                                </div>';
                }else if($engineer_leave->leave_approve_status === 'reject'){
                    $class = $engineer_leave->leave_approve_status === 'approved' ? ' text-green-500' : ' text-red-500';
                    return '<div
                                                class="w-fit px-1.5 py-1 rounded-lg '.$class.' 
                                                        ">
                                                <svg class="w-5 h-5 dark:text-white" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                                    viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2" d="M6 6L18 18M6 18L18 6" />

                                                </svg>
                                            </div>';
                }
                
            })

            
            
            ->rawColumns(['action', 'type_div', 'days_div', 'engineer_name', 'document_div', 'status_div'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(EngineerLeave $model, Request $request): QueryBuilder
    {
        $result = $model->newQuery();
        $result->whereHas('engineer');
        if($request->leave_approve_status == 'pending'){
            $result->where('leave_approve_status', 'pending');
        }else if($request->leave_approve_status == 'approved'){
            $result->where('leave_approve_status', 'approved');
        }else if($request->leave_approve_status == 'reject'){
            $result->where('leave_approve_status', 'reject');
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
                    ->setTableId('engineerleave-table')
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
        return 'EngineerLeave_' . date('YmdHis');
    }
}
