<?php

namespace App\DataTables;

use App\Enums\ModuleEnum;
use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class HolidayDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (Holiday $holiday) {
                $html = '';

                $edit = $delete = $permission = '';

                $user = auth()->user();
                if ($user->can(ModuleEnum::SETTING_HOLIDAY_EDIT->value)) {
                    $edit =
                        '<li>
                        <a  href="'.route('holiday.edit', $holiday->id).'" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white editBtn">Edit</a>
                    </li>';
                }

                if ($user->can(ModuleEnum::SETTING_HOLIDAY_DELETE->value)) {
                    $delete =
                        '<li>
                            <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white del-button text-red-600" data-holiday_id="'.$holiday->id .'">Delete</a>
                        </li>';
                }


                $html =
                    '<button id="dropdownLeftEndButton_' .
                    $holiday->id .
                    '" data-dropdown-toggle="dropdownLeftEnd_' .
                    $holiday->id .
                    '"
                        class="inline-flex items-center p-2 text-sm font-medium text-center text-gray-900 bg-transparent rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none dark:text-white focus:ring-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-600 dropdown-trigger"
                        type="button">
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                            <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                        </svg>
                </button>

                
                <div id="dropdownLeftEnd_' .
                    $holiday->id .
                    '" class="hidden text-white bg-white focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-2 py-2.5 text-left shadow-lg inline-flex items-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800 dropdown-menu">
                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownLeftEndButton_' .
                    $holiday->id .
                    '">' .$edit .'' .$delete .'
                    </ul>
                </div>';

                return $html;
            })
            ->addColumn('status', function (Holiday $holiday) {
                if ($holiday['date'] < Carbon::now()->format('Y-m-d')) {
                    return Blade::render('<x-badge type="danger" label="Expired" class="" />'); 
                }
                if ($holiday['status'] == 0) {
                    return Blade::render('<x-badge type="danger" label="Inactive" class="holiday_active_inactive cursor-pointer" data-holiday_id="' . $holiday->id . '" />');
                }
                return Blade::render('<x-badge type="success" label="Active" class="holiday_active_inactive cursor-pointer" data-holiday_id="' . $holiday->id . '" />');
            })
            ->rawColumns(['action', 'status'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Holiday $model, Request $request): QueryBuilder
    {
        $country = $request->country;
        if ($country) {
            return $model->orderBy('id', 'DESC')->newQuery()->where('country_name', $country);
        }
        return $model->orderBy('id', 'DESC')->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('holiday-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([Button::make('excel'), Button::make('csv'), Button::make('pdf'), Button::make('print'), Button::make('reset'), Button::make('reload')]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [Column::computed('action')->exportable(false)->printable(false)->width(60)->addClass('text-center'), Column::make('id'), Column::make('add your columns'), Column::make('created_at'), Column::make('updated_at')];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Holiday_' . date('YmdHis');
    }
}
