<?php

namespace App\DataTables;

use App\Enums\ModuleEnum;
use App\Models\Bank;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class BankDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (Bank $bank) {
                $html = '';

                $edit = $delete = $permission = '';

                $user = auth()->user();
                if ($user->can(ModuleEnum::SETTING_HOLIDAY_EDIT->value)) {
                    $edit =
                        '<li>
                        <a  href="#" data-modal-target="edit-user-model" data-modal-toggle="edit-bank-model" data-bank_id="' . $bank->id . '" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white editBtn">Edit</a>
                    </li>';
                }

                if ($user->can(ModuleEnum::SETTING_HOLIDAY_DELETE->value)) {
                    $delete =
                        '<li>
                            <a href="#" data-bank_id="' . $bank->id . '" class="del-button block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white del-button text-red-600" >Delete</a>
                        </li>';
                }


                $html =
                    '<button id="dropdownLeftEndButton_' .
                    $bank->id .
                    '" data-dropdown-toggle="dropdownLeftEnd_' .
                    $bank->id .
                    '"
                        class="inline-flex items-center p-2 text-sm font-medium text-center text-gray-900 bg-transparent rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none dark:text-white focus:ring-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-600 dropdown-trigger"
                        type="button">
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                            <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                        </svg>
                </button>

                
                <div id="dropdownLeftEnd_' .
                    $bank->id .
                    '" class="hidden text-white bg-white focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-2 py-2.5 text-left inline-flex items-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800 dropdown-menu shadow-lg">
                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownLeftEndButton_' .
                    $bank->id .
                    '">' .$edit .'' .$delete .'
                    </ul>
                </div>';

                return $html;
                
            })->addColumn('is_active', function (Bank $bank) {
                if ($bank->is_active == 1) {
                    return '<span data-message="Are you sure you want to in-active?" data-bank_id="'.$bank->id.'" class="cursor-pointer bank_active_inactive bg-green-200 text-green-800 dark:bg-green-900 dark:text-green-300  text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">
                        Active
                    </span>';
                }
                return '<span data-message="Are you sure you want to active?" data-bank_id="'.$bank->id.'" class="cursor-pointer bank_active_inactive bg-red-200 text-red-800 dark:bg-red-900 dark:text-red-300  text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">
                        In Active
                    </span>';
            })
            ->addColumn('bank_and_country', function (Bank $bank) {
                return $bank->bank_name . ', ' . $bank->country;
            })
            ->rawColumns(['action', 'is_active'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Bank $model): QueryBuilder
    {
        return $model->orderBy('id', 'desc')->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('bank-table')
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
            Column::make('bank_and_country'),
            Column::make('is_active'),
            Column::make('action'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Bank_' . date('YmdHis');
    }
}
