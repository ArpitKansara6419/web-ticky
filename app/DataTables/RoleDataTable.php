<?php

namespace App\DataTables;

use App\Enums\ModuleEnum;
use App\Models\Role;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class RoleDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (Role $role) {
                $html = "";

                $edit = $delete = $permission = "";

                $user  = auth()->user();
                if($user->can(ModuleEnum::SETTING_ROLE_EDIT->value))
                {
                    $edit = '<li>
                            <a  href="#" data-modal-target="edit-role-model" data-modal-toggle="edit-role-model" data-role_id="' . $role->id . '" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white editBtn">Edit</a>
                        </li>';
                }
                

                if($user->can(ModuleEnum::SETTING_ROLE_DELETE->value))
                {
                    $delete ='<li>
                                <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white del-button text-red-600" data-role_id="'.$role->id.'">Delete</a>
                            </li>';
                }

                if($user->can(ModuleEnum::SETTING_ROLE_PERMISSION->value))
                {
                    $permission = '<li>
                            <a  href="'.route("role.permissionView", $role->id).'" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Permission</a>
                        </li>';
                }
                


                $html = '<button id="dropdownLeftEndButton_'.$role->id.'" data-dropdown-toggle="dropdownLeftEnd_'.$role->id.'"  
                            class="inline-flex items-center p-2 text-sm font-medium text-center text-gray-900 bg-transparent rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none dark:text-white focus:ring-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-600 dropdown-trigger"
                            type="button">
                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                                <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                            </svg>
                    </button>

                    
                    <div id="dropdownLeftEnd_'.$role->id.'" class="hidden text-white bg-white focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-2 py-2.5 inline-flex text-left shadow-lg dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800 dropdown-menu">
                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownLeftEndButton_'.$role->id.'">
                            '.$edit.'
                            '.$permission.'
                            '.$delete.'
                        </ul>
                    </div>';

                return $html;
            })
            ->rawColumns(['action', 'is_active'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Role $model): QueryBuilder
    {
        return $model
            ->where('name', '<>', 'superadmin')
            ->orderBy('id', 'DESC')->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('role-table')
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
            Column::make('guard_name'),
            Column::make('created_at'),
            Column::make('updated_at'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Role_' . date('YmdHis');
    }
}
