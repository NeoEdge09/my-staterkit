<?php

namespace App\DataTables\Admin;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class   MenusDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('name', function ($menu) {
                return $menu->name;
            })
            ->addColumn('icon', function ($menu) {
                return '<i class="' . $menu->icon . '"></i>';
            })
            ->addColumn('route', function ($menu) {
                return $menu->route ?? '-';
            })
            ->addColumn('permission', function ($menu) {
                return $menu->permission ? $menu->permission : '-';
            })
            ->editColumn('parent', function ($menu) {
                return $menu->parent ? $menu->parent->name : '-';
            })
            ->addColumn('children', function ($menu) {
                return $menu->children->count() ?? '0';
            })
            ->addColumn('action', function ($menu) {
                $actions = [
                    [
                        'icon' => 'ti ti-pencil',
                        'label' => 'Edit',
                        'data' => [
                            'bs-toggle' => 'modal',
                            'bs-target' => '#editModal',
                            'id' => $menu->id
                        ],
                        'class' => 'text-warning edit-item'
                    ]
                ];
                return view('components.action-menu', compact('actions'))->render();
            })
            ->rawColumns(['name', 'icon', 'route', 'permission', 'parent', 'children', 'action'])
            ->addIndexColumn();
    }


    /**
     * Get the query source of dataTable.
     */
    public function query(Menu $model): QueryBuilder
    {
        return $model->with('parent', 'children')->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('menus-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Blfrtip') // l=length, f=filter, r=processing, t=table, i=info, p=pagination
            ->orderBy(1)
            ->selectStyleSingle()
            ->parameters([
                'serverSide' => true,
                'scrollX' => true,
                'responsive' => true,
                'autoWidth' => false,
                'pageLength' => 10,
                'lengthMenu' => [[10, 25, 50, -1], [10, 25, 50, 'All']],
                'order' => [[1, 'asc']]
            ])
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
            Column::make('DT_RowIndex')
                ->title('#')
                ->searchable(false)
                ->orderable(false)
                ->width(30),
            Column::make('name')
                ->title('Name')
                ->searchable(true)
                ->orderable(true)
                ->data('name'),
            Column::make('icon')
                ->title('Icon')
                ->searchable(true)
                ->orderable(true)
                ->data('icon'),
            Column::make('route')
                ->title('Route')
                ->searchable(true)
                ->orderable(true)
                ->data('route'),
            Column::make('permission')
                ->title('Permission')
                ->searchable(true)
                ->orderable(true)
                ->data('permission'),
            Column::make('parent')
                ->title('Parent')
                ->searchable(true)
                ->orderable(true)
                ->data('parent'),
            Column::make('children')
                ->title('Children')
                ->searchable(false)
                ->orderable(false),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center')
                ->orderable(false)
                ->searchable(false)
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Menus_' . date('YmdHis');
    }
}
