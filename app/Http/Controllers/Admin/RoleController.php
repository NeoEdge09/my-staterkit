<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use App\Http\Resources\Admin\RoleResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::query()
            ->select('id', 'name', 'guard_name', 'created_at')
            ->get();
        $permissions  = Permission::query()
            ->select('name')
            ->get();
        if ($request->ajax()) {
            return datatables()->of($roles)
                ->addColumn('id', function ($role) {
                    return $role->id;
                })
                ->addColumn('name', function ($role) {
                    return $role->name;
                })
                ->addColumn('permissions', function ($roles) use ($permissions) {
                    $badgePermissions = '';
                    $permissionCount = 0;
                    $maxBadges = 16;

                    foreach ($permissions as $permission) {
                        if ($roles->hasPermissionTo($permission->name)) {
                            $permissionCount++;
                            if ($permissionCount <= $maxBadges) {
                                $badgePermissions .= '<span class="badge text-bg-primary me-1 mb-1">' . $permission->name . '</span>';
                            }
                        }
                    }

                    if ($permissionCount > $maxBadges) {
                        $remaining = $permissionCount - $maxBadges;
                        $badgePermissions .= '<span class="badge text-bg-secondary ms-1">+' . $remaining . ' more</span>';
                    }

                    return $badgePermissions ?: '<span class="text-muted">No permissions</span>';
                })
                ->addColumn('guard_name', function ($role) {
                    return $role->guard_name;
                })
                ->addColumn('created_at', function ($role) {
                    return $role->created_at->format('d M Y');
                })
                ->addColumn('action', function ($role) {
                    $actions = [
                        [
                            'icon' => 'ti ti-pencil text-warning',
                            'label' => 'Edit / Tetapkan Izin',
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
                ->rawColumns(['action', 'permissions'])
                ->make(true);
        }
        return view('Pages.Admin.Roles.index', [
            'page_settings' => [
                'title' => 'Role',
                'subtitle' => 'Daftar Role Pada Platform Ini',
                'action' => [
                    'create' => [
                        'bs-target' => '#createModalRoles',
                        'id_modal' => 'createModalRoles',
                        'id_form' => 'createFormrRoles',
                        'label' => 'Tambah Role',
                        'route' => route('admin.roles.store')
                    ],
                    'edit' => [
                        'id_modal' => 'editModalRoles',
                        'id_form' => 'editFormRoles',
                        'getData_route' => route('admin.roles.show', ['role' => ':id']),
                        'update_route' => route('admin.roles.update', ['role' => ':id']),
                    ],
                    'delete' => [
                        'id_modal' => 'deleteModalRoles',
                        'id_form' => 'deleteFormRoles',
                        'delete_route' => route('admin.roles.destroy', ['role' => ':id']),
                    ],
                    'view' => [
                        'id_modal' => 'viewModalRoles',
                        'route_show' => route('admin.roles.show', ['role' => ':id']),
                    ],
                ]
            ],
            'table_settings' => [
                'id' => 'roles-table',
                'tableAjax' => route('admin.roles.index'),
                'columns' => [
                    ['data' => 'id', 'name' => 'id', 'title' => 'ID'],
                    ['data' => 'name', 'name' => 'name', 'title' => 'Role'],
                    ['data' => 'guard_name', 'name' => 'guard_name', 'title' => 'Guard'],
                    ['data' => 'permissions', 'name' => 'permissions', 'title' => 'Permissions'],
                    ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Dibuat Pada'],
                    [
                        'data' => 'action',
                        'name' => 'action',
                        'title' => 'Aksi',
                        'orderable' => false,
                        'searchable' => false
                    ],
                ],
            ],
            'roles' => $roles,
            'all_ermissions' => $permissions,
            'permission_groups' => User::getpermissionGroups(),
        ]);
    }

    public function store(RoleRequest $request)
    {
        $validator = Validator::make($request->all(), $request->rules());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 422);
        }

        $role = Role::create($request->validated());

        $permissions = $request->input('permissions');
        if (!empty($permissions)) {
            $role->name = $request->name;
            $role->save();
            $role->syncPermissions($permissions);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Role berhasil ditambahkan',
            'data' => new RoleResource($role)
        ]);
    }

    public function show(Role $role): JsonResponse
    {
        $role->load('permissions');

        return response()->json([
            'message' => 'Role retrieved successfully',
            'role' => new RoleResource($role)
        ]);
    }

    public function update(RoleRequest $request, Role $role): JsonResponse
    {

        $validator = Validator::make($request->all(), $request->rules());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 422);
        }

        $permissions = $request->input('permissions');
        if (!empty($permissions)) {
            $role->name = $request->name;
            $role->save();
            $role->syncPermissions($permissions);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Role berhasil diubah',
            'data' => new RoleResource($role)
        ]);
    }

    public function destroy(Role $role): JsonResponse
    {
        $role->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Role berhasil dihapus'
        ]);
    }
}
