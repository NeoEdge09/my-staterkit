<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RouteAccessRequest;
use App\Http\Resources\Admin\RouteAccessResource;
use App\Models\RouteAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RouteAccessController extends Controller
{
    public function index(Request $request)
    {
        $route_accesses = RouteAccess::with('role', 'permission')->get();

        if ($request->ajax()) {
            return datatables()->of($route_accesses)
                ->addColumn('id', function ($route_accesses) {
                    return $route_accesses->id;
                })
                ->addColumn('route_name', function ($route_accesses) {
                    return $route_accesses->route_name;
                })
                ->addColumn('role', function ($route_accesses) {
                    return $route_accesses->role->name;
                })

                ->addColumn('permission', function ($route_accesses) {
                    return $route_accesses->permission->name;
                })

                ->addColumn('created_at', function ($route_accesses) {
                    return $route_accesses->created_at->format('d M Y');
                })
                ->addColumn('action', function ($route_accesses) {
                    $actions = [
                        [
                            'icon' => 'ti ti-pencil text-warning',
                            'label' => 'Edit',
                            'class' => 'text-dark edit-item',
                            'data-id' => $route_accesses->id,
                        ],
                        [
                            'icon' => 'ti ti-trash text-danger',
                            'label' => 'Delete',
                            'class' => 'text-dark delete-item',
                            'data-id' => $route_accesses->id
                        ]
                    ];
                    return view('components.action-menu', compact('actions'))->render();
                })
                ->rawColumns(['action', 'permissions'])
                ->make(true);
        }

        $roles = Role::query()
            ->select('id', 'name')
            ->get();

        $permissions = Permission::query()
            ->select('id', 'name')
            ->get();

        return view('Pages.Admin.RouteAccess.index', [
            'page_settings' => [
                'title' => 'Route Access',
                'subtitle' => 'List of Route Accesses on this platform',
                'action' => [
                    'create' => [
                        'bs-target' => '#createModalRouteAccess',
                        'id_modal' => 'createModalRouteAccess',
                        'id_form' => 'createFormRouteAccess',
                        'label' => 'Add Route Access',
                        'route' => route('admin.route-accesses.store')
                    ],
                    'edit' => [
                        'id_modal' => 'editModalRouteAccess',
                        'id_form' => 'editFormRouteAccess',
                        'getData_route' => route('admin.route-accesses.show', ['routeAccess' => ':id']),
                        'update_route' => route('admin.route-accesses.update', ['routeAccess' => ':id']),
                    ],
                    'delete' => [
                        'id_modal' => 'deleteModalRouteAccess',
                        'id_form' => 'deleteFormRouteAccess',
                        'delete_route' => route('admin.route-accesses.destroy', ['routeAccess' => ':id']),
                    ],
                    'view' => [
                        'id_modal' => 'viewModalRouteAccess',
                        'route_show' => route('admin.route-accesses.show', ['routeAccess' => ':id']),
                    ],
                ]

            ],
            'table_settings' => [
                'id' => 'route-accesses-table',
                'tableAjax' => route('admin.route-accesses.index'),
                'columns' => [
                    ['data' => 'id', 'name' => 'id', 'title' => 'ID'],
                    ['data' => 'route_name', 'name' => 'route_name', 'title' => 'Route Name'],
                    ['data' => 'role', 'name' => 'role', 'title' => 'Role'],
                    ['data' => 'permission', 'name' => 'permission', 'title' => 'Permission'],
                    ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Created At'],
                    ['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false],
                ],
            ],

            'roles' => $roles,
            'permissions' => $permissions,
            'routes' => collect(Route::getRoutes())->map(function ($route) {
                return [
                    'value' => $route->getName(),
                    'label' => $route->getName(),
                ];
            })->filter(),
        ]);
    }
    public function store(RouteAccessRequest $request)
    {
        $validated = $request->validated();

        $route_access = RouteAccess::create([
            'route_name' => $validated['route_name'],
            'role_id' => $validated['role'],
            'permission_id' => $validated['permission']
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Route Access has been added successfully',
            'data' => new RouteAccessResource($route_access)
        ]);
    }

    public function update(RouteAccessRequest $request, RouteAccess $routeAccess)
    {
        $validated = $request->validated();

        $routeAccess->update([
            'route_name' => $validated['route_name'],
            'role_id' => $validated['role'],
            'permission_id' => $validated['permission']
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Route Access has been updated successfully',
            'data' => new RouteAccessResource($routeAccess)
        ]);
    }
    public function show(RouteAccess $routeAccess)
    {
        $routeAccess->load('role', 'permission');
        return response()->json([
            'status' => 'success',
            'data' => new RouteAccessResource($routeAccess)
        ]);
    }



    public function destroy(RouteAccess $routeAccess)
    {
        $routeAccess->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Route Access berhasil dihapus'
        ]);
    }
}
