<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MenuRequest;
use App\Http\Resources\Admin\MenuResource;
use App\Models\Menu;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $menus = Menu::query()
            ->select('id', 'name', 'icon', 'route', 'role', 'permission', 'order', 'parent_id')
            ->with('children', 'parent')
            ->get();
        if ($request->ajax()) {
            return datatables()->of($menus)
                ->addColumn('id', function ($menu) {
                    return $menu->id;
                })
                ->addColumn('name', function ($menu) {
                    return $menu->name;
                })
                ->addColumn('icon', function ($menu) {
                    return '<i class="' . $menu->icon . '"></i>';
                })
                ->addColumn('role', function ($menu) {
                    return $menu->role ?? '-';
                })
                ->addColumn('permission', function ($menu) {
                    return $menu->permission ?? '-';
                })
                ->addColumn('route', function ($menu) {
                    return $menu->route ?? '-';
                })
                ->addColumn('parent', function ($menu) {
                    return $menu->parent ? $menu->parent->name : '-';
                })
                ->addColumn('children', function ($menu) {
                    return $menu->children->count() ?? 0;
                })
                ->addColumn('action', function ($menu) {
                    $actions = [
                        [
                            'icon' => 'ti ti-eye text-info',
                            'label' => 'Lihat Detail',
                            'class' => 'text-dark view-item',
                            'data-id' => $menu->id
                        ],
                        [
                            'icon' => 'ti ti-pencil text-warning',
                            'label' => 'Edit',
                            'class' => 'text-dark edit-item',
                            'data-id' => $menu->id
                        ],
                        [
                            'icon' => 'ti ti-trash text-danger',
                            'label' => 'Hapus',
                            'class' => 'text-dark delete-item',
                            'data-id' => $menu->id

                        ],
                    ];
                    return view('components.action-menu', compact('actions'))->render();
                })
                ->addIndexColumn()
                ->rawColumns([
                    'name',
                    'icon',
                    'route',
                    'permission',
                    'parent',
                    'children',
                    'action'
                ])
                ->make(true);
        }
        return view('Pages.Admin.Menus.index', [
            'page_settings' => [
                'title' => 'Menus',
                'subtitle' => 'List menu pada platform ini',
                'action' => [
                    'create' => [
                        'bs-target' => '#createModalMenus',
                        'id_modal' => 'createModalMenus',
                        'id_form' => 'createFormMenus',
                        'label' => 'Tambah Menu',
                        'route' => route('admin.menus.store'),
                    ],
                    'edit' => [
                        'id_modal' => 'editModalMenus',
                        'id_form' => 'editFormMenus',
                        'getData_route' => route('admin.menus.show', ['menu' => ':id']),
                        'update_route' => route('admin.menus.update', ['menu' => ':id']),
                    ],
                    'delete' => [
                        'id_modal' => 'deleteModalMenus',
                        'id_form' => 'deleteFormMenus',
                        'delete_route' => route('admin.menus.destroy', ['menu' => ':id']),
                    ],
                    'view' => [
                        'id_modal' => 'viewModalMenus',
                        'route_show' => route('admin.menus.show', ['menu' => ':id']),
                    ],
                ],
            ],
            'table_settings' => [
                'id' => 'menus-table',
                'tableAjax' => route('admin.menus.index'),
                'columns' => [
                    ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
                    ['data' => 'icon', 'name' => 'icon', 'title' => 'Icon'],
                    ['data' => 'role', 'name' => 'role', 'title' => 'Role'],
                    ['data' => 'permission', 'name' => 'permission', 'title' => 'Permission'],
                    ['data' => 'route', 'name' => 'route', 'title' => 'Route'],
                    ['data' => 'parent', 'name' => 'parent', 'title' => 'Parent'],
                    ['data' => 'children', 'name' => 'children', 'title' => 'Children'],
                    ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false, 'title' => 'Action'],
                ],
            ],
            'menus' => $menus,
            'routes' => collect(Route::getRoutes())->map(function ($route) {
                return [
                    'value' => $route->getName(),
                    'label' => $route->getName(),
                ];
            })->filter(),
            'roles' => Role::all(),
            'permissions' => Permission::all(),
        ]);
    }
    public function store(MenuRequest $request)
    {
        $validator = Validator::make($request->all(), $request->rules());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 422);
        }
        $menu = Menu::create($request->validated());
        return response()->json([
            'status' => 'success',
            'message' => 'Menu Berhasil ditambahkan',
            'data' => new MenuResource($menu),
        ]);
    }
    public function show(Menu $menu): JsonResponse
    {
        return response()->json(['data' => new MenuResource($menu)]);
    }

    public function update(MenuRequest $request, Menu $menu): JsonResponse
    {
        $validator = Validator::make($request->all(), $request->rules());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 422);
        }

        $menu->update($request->validated());
        return response()->json([
            'status' => 'success',
            'message' => 'Menu Berhasil diubah',
            'data' => new MenuResource($menu),
        ]);
    }

    public function destroy(Menu $menu): JsonResponse
    {
        $menu->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Menu Berhasil dihapus',
        ]);
    }
}
