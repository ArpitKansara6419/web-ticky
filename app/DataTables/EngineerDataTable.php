<?php

namespace App\DataTables;

use App\Enums\ModuleEnum;
use App\Models\Engineer;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Blade;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Html\Editor\Fields\Hidden;
use Yajra\DataTables\Services\DataTable;

class EngineerDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (Engineer $engineer) {
                $html = "";

                $view = $delete = "";

                $user  = auth()->user();
                if($user->can(ModuleEnum::ENGINEER_DETAIL->value))
                {
                    $view = '<li>
                            <a  href="'.route('engg.show', $engineer->id).'" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">View</a>
                        </li>';
                }
                

                if($user->can(ModuleEnum::ENGINEER_DELETE->value))
                {
                    $delete ='<li>
                                <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white del-button text-red-600" data-engineer_id="'.$engineer->id.'">Delete</a>
                            </li>';
                }
                


                $html = '<button id="dropdownLeftEndButton_'.$engineer->id.'" data-dropdown-toggle="dropdownLeftEnd_'.$engineer->id.'"  
                            class="inline-flex items-center p-2 text-sm font-medium text-center text-gray-900 bg-transparent rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none dark:text-white focus:ring-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-600 dropdown-trigger"
                            type="button">
                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                                <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                            </svg>
                    </button>

                    
                    <div id="dropdownLeftEnd_'.$engineer->id.'" class="hidden text-white bg-white focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm shadow-lg px-2 py-2.5 text-left inline-flex items-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800 dropdown-menu">
                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownLeftEndButton_'.$engineer->id.'">
                            '.$view.'
                            '.$delete.'
                        </ul>
                    </div>';

                return $html;
            })
            ->addColumn('status', function (Engineer $engineer) {
                if($engineer['status'] == 0)
                {
                    return Blade::render('<x-badge type="danger" label="Inactive" class="" />');
                }
                return Blade::render('<x-badge type="success" label="Active" class="" />');
            })
            ->filterColumn('engineer_div', function ($query, $keyword) {
                $query->where("first_name", 'like', '%' . $keyword . '%')
                    ->orWhere("engineer_code", 'like', '%' . $keyword . '%')
                    ->orWhere("last_name", 'like', '%' . $keyword . '%')
                    ->orWhere("addr_city", 'like', '%' . $keyword . '%')
                    ->orWhere("addr_country", 'like', '%' . $keyword . '%')
                    ->orWhere("timezone", 'like', '%' . $keyword . '%')
                    ->orWhere("email", 'like', '%' . $keyword . '%');
            })
            ->addColumn('engineer_div', function (Engineer $engineer) {

                $total = 0;
                $result = [];

                $models = [
                    [
                        'key' => 'education_details',
                        'model' => 'EngineerEducation',
                    ],
                    [
                        'key' => 'id_documents',
                        'model' => 'EngineerDocument',
                    ],
                    [
                        'key' => 'right_to_work',
                        'model' => 'RightToWork',
                    ],
                    [
                        'key' => 'payment_details',
                        'model' => 'EngineerPaymentDetail',
                    ],
                ];

                // Check related models dynamically
                foreach ($models as $model) {
                    $modelClass = "App\\Models\\{$model['model']}"; // Adjust namespace if needed
                    $result[$model['key']] = $modelClass::where('user_id', $engineer->id)->exists() ? 1 : 0;
                    $total += $result[$model['key']];
                }

                // Check personal details in Engineer model
                $personalDetailsFields = [
                    'first_name',
                    'last_name',
                    'email',
                    'contact',
                    'gender',
                    'birthdate',
                    'nationality',
                    'addr_apartment',
                    'addr_street',
                    'addr_address_line_1',
                    'addr_address_line_2',
                    'addr_zipcode',
                    'addr_city',
                    'addr_country',
                ];

                // Check if any of the personal details fields are not empty
                $hasPersonalDetails = collect($personalDetailsFields)
                    ->some(fn($field) => !empty($engineer->{$field}));

                $result['personal_details'] = $hasPersonalDetails ? 1 : 0;

                // Optionally add engineer's id or other information
                $result['engineer_id'] = $engineer->id;

                $total += $result['personal_details'];

                // Store the result for the current engineer
                $engineerResults[$engineer->id] = $total;

                $per = $engineerResults[$engineer->id] * 20;

                $active_inactive = $engineer['status'] == 1 ? "bg-green-500" : "bg-red-500";

                $image = $engineer["profile_image"] ? asset("storage/" . $engineer["profile_image"]) : asset("user_profiles/user/user.png");
                $html = '<div
                                class="font-medium flex gap-2 p-1 items-center text-gray-900 whitespace-nowrap dark:text-white ">
                                <div class="relative me-4">
                                    <img class="w-10 h-10 rounded-full border-blue-800 border-2" src="'.$image.'" alt="profile image">
                                    <span class="top-0 start-7 absolute w-3.5 h-3.5 '.$active_inactive.' border-2 border-white dark:border-gray-800 rounded-full"></span>
                                </div>
                                <!--<img class="w-10 h-10 rounded-full" src="'.$image.'"
                                    alt="Rounded avatar">-->
                                <div class="">
                                    <p class="capitalize">
                                        <a href='.route('engg.show', $engineer->id).'>'.$engineer['first_name'] . ' ' . $engineer['last_name'].'</a>
                                    </p>
                                    <p class="capitalize text-xs text-blue-800 ">#'.$engineer['engineer_code'] .'</p>
                                    <p class="text-gray-400">'. $engineer['email'].'</p>
                                </div>
                            </div>
                            
                            <div class="w-48 bg-gray-200 rounded-full dark:bg-gray-700">
                                <div class="bg-primary-dark text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full" style="width: '.$per.'%"> '.$per.'% </div>
                            </div>';

                return $html;
            })
            ->addColumn('timezone', function (Engineer $engineer) {
                $gm = fetchTimezone($engineer['timezone']);
                $gmt = isset($gm['gmtOffsetName']) ? "(".$gm['gmtOffsetName'].")" : '';
                return $engineer->timezone.' '.$gmt;
            })
            ->addColumn('full_address_timezone', function (Engineer $engineer) {
                return $engineer['full_address_timezone'];
            })
            ->addColumn('location_div', function (Engineer $engineer) {
                $address = $engineer->addr_city;

                if($engineer->addr_city && $engineer->addr_country)
                {
                    $address .= ', ';
                }

                $address .= ' '.$engineer->addr_country;

                return $address;
            })
            ->addColumn('timezone_div', function (Engineer $engineer) {
                return Blade::render('<x-timezone timezone="'.e($engineer->timezone).'"/>');
            })
            ->addColumn('job_type', function (Engineer $engineer) {
                return $engineer['job_type'] ? ucfirst(str_replace('_', ' ', $engineer['job_type'])) : '-';
            })
            ->addColumn('contact', function (Engineer $engineer) {
                return '<span class="text-nowrap">+'.$engineer['country_code'].' '.$engineer['contact'].'</span>';
            })
            ->rawColumns(['action', 'status', 'first_name', 'engineer_div', 'location_div', 'timezone_div', 'contact'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Engineer $model): QueryBuilder
    {
        return $model->orderBy('id', 'DESC')->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('engineer-table')
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
            Column::make('engineer_code'),
            Column::make('first_name'),
            Column::make('contact'),
            Column::make('job_title'),
            Column::make('job_type'),
            Column::make('addr_city'),
            Column::make('timezone'),
            Column::make('status'),
            Column::make('full_address_timezone'),
            Column::make('engineer_div'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Engineer_' . date('YmdHis');
    }
}
