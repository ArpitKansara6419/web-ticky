<?php

namespace App\DataTables;

use App\Enums\ModuleEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (User $user) {
                $html = "";

                $edit = $delete = "";

                $authUser  = auth()->user();
                if($authUser->can(ModuleEnum::SETTING_SYSTEM_USERS_EDIT->value))
                {
                    $edit = '<li>
                            <a href="#" data-modal-target="edit-user-model" data-modal-toggle="edit-user-model" data-user_id="' . $user->id . '" class="editBtn block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white" >Edit</a>
                        </li>';
                }
                

                if($authUser->can(ModuleEnum::SETTING_SYSTEM_USERS_DELETE->value))
                {
                    $delete ='<li>
                                <a href="#" class="del-button block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white del-button text-red-600" data-user_id="'.$user->id.'">Delete</a>
                            </li>';
                }
                


                $html = '<button id="dropdownLeftEndButton_'.$user->id.'" data-dropdown-toggle="dropdownLeftEnd_'.$user->id.'"  
                            class="inline-flex items-center p-2 text-sm font-medium text-center text-gray-900 bg-transparent rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none dark:text-white focus:ring-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-600 dropdown-trigger"
                            type="button">
                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                                <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                            </svg>
                    </button>

                    
                    <div id="dropdownLeftEnd_'.$user->id.'" class="hidden text-white bg-white focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-2 py-2.5 text-left inline-flex items-center shadow-lg dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800 dropdown-menu">
                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownLeftEndButton_'.$user->id.'">
                            '.$edit.'
                            '.$delete.'
                        </ul>
                    </div>';

                return $html;
            })
            ->addColumn('roles', function (User $user) {
                return $user->getRoleNames()->map(function($role) {
                    return '<span class="cursor-pointer bg-green-200 text-green-800 dark:bg-green-900 dark:text-green-300  text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">
                        '.$role.'
                    </span>';
                })->implode(' ');
            })
            ->rawColumns(['action', 'roles'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(User $model): QueryBuilder
    {
        return $model->with('roles')
            ->where('email', '<>', 'admin@gmail.com')
            ->orderBy('created_at', 'DESC')->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('user-table')
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
            Column::make('name'),
            Column::make('email'),
            Column::make('roles'),
            Column::make('action'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'User_' . date('YmdHis');
    }
}
