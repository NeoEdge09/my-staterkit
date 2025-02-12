<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PermissionRequest;
use App\Http\Resources\Admin\PermissionResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $permissions = Permission::query()
            ->select('id', 'name', 'module', 'guard_name', 'created_at')
            ->get();

        $roles = Role::query()
            ->select('name')
            ->get();

        if ($request->ajax()) {
            return datatables()->of($permissions)
                ->addColumn('id', function ($permission) {
                    return $permission->id;
                })
                ->addColumn('name', function ($permission) {
                    return $permission->name;
                })
                ->addColumn('module', function ($permission) {
                    return $permission->module;
                })
                ->addColumn('roles', function ($permissions) use ($roles) {
                    $badgeRoles = '';
                    $roleCount = 0;
                    $maxBadges = 16;

                    foreach ($roles as $role) {
                        if ($permissions->hasRole($role->name)) {
                            $roleCount++;
                            if ($roleCount <= $maxBadges) {
                                $badgeRoles .= '<span class="badge text-bg-primary me-1 mb-1">' . $role->name . '</span>';
                            }
                        }
                    }

                    if ($roleCount > $maxBadges) {
                        $remaining = $roleCount - $maxBadges;
                        $badgeRoles .= '<span class="badge text-bg-secondary ms-1">+' . $remaining . ' more</span>';
                    }

                    return $badgeRoles ?: '<span class="text-muted">No roles</span>';
                })
                ->addColumn('guard_name', function ($permission) {
                    return $permission->guard_name;
                })
                ->addColumn('created_at', function ($permission) {
                    return $permission->created_at->format('d M Y');
                })
                ->addColumn('action', function ($role) {
                    $actions = [
                        [
                            'icon' => 'ti ti-pencil text-warning',
                            'label' => 'Edit Permissions',
                            'class' => 'text-dark edit-item',
                            'data-id' => $role->id,
                        ],
                        [
                            'icon' => 'ti ti-trash text-danger',
                            'label' => 'Hapus',
                            'class' => 'text-dark delete-item',
                            'data-id' => $role->id
                        ]
                    ];
                    return view('components.action-menu', compact('actions'))->render();
                })
                ->rawColumns(['roles', 'action'])
                ->make(true);
        }

        return view('Pages.Admin.Permissions.index', [
            'page_settings' => [
                'title' => 'Permissions',
                'subtitle' => 'Dafar Permissions Pada Platform Ini',
                'action' => [
                    'create' => [
                        'bs-target' => '#createModalPermissions',
                        'id_modal' => 'createModalPermissions',
                        'id_form' => 'createFormrPermissions',
                        'label' => 'Tambah Permissions',
                        'route' => route('admin.permissions.store')
                    ],
                    'edit' => [
                        'id_modal' => 'editModalPermissions',
                        'id_form' => 'editFormPermissions',
                        'getData_route' => route('admin.permissions.show', ['permission' => ':id']),
                        'update_route' => route('admin.permissions.update', ['permission' => ':id']),
                    ],
                    'delete' => [
                        'id_modal' => 'deleteModalPermissions',
                        'id_form' => 'deleteFormPermissions',
                        'delete_route' => route('admin.permissions.destroy', ['permission' => ':id']),
                    ],
                    'view' => [
                        'id_modal' => 'viewModalPermissions',
                        'route_show' => route('admin.permissions.show', ['permission' => ':id']),
                    ],
                ]
            ],
            'table_settings' => [
                'id' => 'permissions-table',
                'tableAjax' => route('admin.permissions.index'),
                'columns' => [
                    ['data' => 'id', 'name' => 'id', 'title' => 'ID'],
                    ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
                    ['data' => 'module', 'name' => 'module', 'title' => 'Module'],
                    ['data' => 'roles', 'name' => 'roles', 'title' => 'Roles'],
                    ['data' => 'guard_name', 'name' => 'guard_name', 'title' => 'Guard Name'],
                    ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Created At'],
                    ['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false]
                ]
            ]
        ]);
    }

    public function store(PermissionRequest $request): JsonResponse
    {
        $validator = Validator::make($request->all(), $request->rules());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 422);
        }
        $module = strtolower($request->module);
        $createdPermissions = [];

        $permissionNames = generateModulePermissions(
            $module,
            $request->permission_type === 'all' ? 'all' : $request->permissions
        );

        foreach ($permissionNames as $permissionName) {
            $createdPermissions[] = Permission::create([
                'name' => $permissionName,
                'module' => $module,
                'guard_name' => $request->guard_name
            ]);
        }

        return response()->json([
            'message' => 'Permission berhasil ditambahkan',
            'permissions' => PermissionResource::collection($createdPermissions)
        ]);
    }

    public function update(PermissionRequest $request, Permission $permission): JsonResponse
    {
        $validator = Validator::make($request->all(), $request->rules());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 422);
        }

        $module = strtolower($request->module);

        $permission->update([
            'name' => $request->name,
            'module' => $module,
            'guard_name' => $request->guard_name
        ]);

        return response()->json([
            'message' => 'Permission berhasil diperbarui',
            'permission' => new PermissionResource($permission)
        ]);
    }

    public function destroy(Permission $permission): JsonResponse
    {
        $permission->delete();

        return response()->json([
            'message' => 'Permission deleted successfully'
        ]);
    }

    public function show(Permission $permission): JsonResponse
    {
        $permission->load('roles');

        return response()->json([
            'message' => 'Success',
            'permission' => new PermissionResource($permission)
        ]);
    }
}
