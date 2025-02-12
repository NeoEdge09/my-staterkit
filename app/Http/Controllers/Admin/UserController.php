<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->select('id', 'name', 'email', 'email_verified_at', 'created_at')
            ->with('roles', 'permissions')
            ->get();


        if ($request->ajax()) {
            return datatables()->of($users)
                ->addColumn('id', function ($user) {
                    return $user->id;
                })
                ->addColumn('name', function ($user) {
                    return $user->name;
                })
                ->addColumn('email', function ($user) {
                    return $user->email;
                })
                ->addColumn('roles', function ($user) {
                    $badgeRoles = '';
                    $roleCount = 0;
                    $maxBadges = 3;

                    foreach ($user->roles as $role) {
                        $roleCount++;
                        if ($roleCount <= $maxBadges) {
                            $badgeRoles .= '<span class="badge text-bg-primary me-1 mb-1">' . $role->name . '</span>';
                        }
                    }

                    if ($roleCount > $maxBadges) {
                        $remaining = $roleCount - $maxBadges;
                        $badgeRoles .= '<span class="badge text-bg-secondary ms-1">+' . $remaining . ' more</span>';
                    }

                    return $badgeRoles ?: '<span class="text-muted">No roles</span>';
                })
                ->addColumn('permissions', function ($user) {
                    $badgePermissions = '';
                    $permissionCount = 0;
                    $maxBadges = 3;

                    foreach ($user->permissions as $permission) {
                        $permissionCount++;
                        if ($permissionCount <= $maxBadges) {
                            $badgePermissions .= '<span class="badge text-light-primary me-1 mb-1">' . $permission->name . '</span>';
                        }
                    }

                    if ($permissionCount > $maxBadges) {
                        $remaining = $permissionCount - $maxBadges;
                        $badgePermissions .= '<span class="badge text-bg-secondary ms-1">+' . $remaining . ' more</span>';
                    }

                    return $badgePermissions ?: '<span class="text-muted">No direct permissions</span>';
                })
                ->addColumn('created_at', function ($user) {
                    return $user->created_at->format('d M Y');
                })
                ->addColumn('action', function ($user) {
                    $actions = [
                        [
                            'icon' => 'ti ti-eye text-info',
                            'label' => 'Lihat Detail',
                            'class' => 'text-dark view-item',
                            'data-id' => $user->id
                        ],
                        [
                            'icon' => 'ti ti-pencil text-warning',
                            'label' => 'Edit',
                            'class' => 'text-dark edit-item',
                            'data-id' => $user->id
                        ],
                        [
                            'icon' => 'ti ti-trash text-danger',
                            'label' => 'Hapus',
                            'class' => 'text-dark delete-item',
                            'data-id' => $user->id
                        ],
                    ];
                    return view('components.action-menu', compact('actions'))->render();
                })
                ->rawColumns(['action', 'roles', 'permissions'])
                ->make(true);
        }

        // Get all roles and permissions for forms
        $roles = Role::all();
        $permissions = Permission::all();

        return view('Pages.Admin.Users.index', [
            'page_settings' => [
                'title' => 'Daftar Pengguna',
                'subtitle' => 'Daftar Pengguna Pada Platform Ini',
                'action' => [
                    'create' => [
                        'bs-target' => '#createModalUsers',
                        'id_modal' => 'createModalUsers',
                        'id_form' => 'createFormUsers',
                        'label' => 'Tambah Pengguna',
                        'route' => route('admin.users.store'),
                    ],
                    'edit' => [
                        'id_modal' => 'editModalUsers',
                        'id_form' => 'editFormUsers',
                        'getData_route' => route('admin.users.show', ['user' => ':id']),
                        'update_route' => route('admin.users.update', ['user' => ':id']),
                    ],
                    'delete' => [
                        'id_modal' => 'deleteModalUsers',
                        'id_form' => 'deleteFormUsers',
                        'delete_route' => route('admin.users.destroy', ['user' => ':id']),
                    ],
                    'view' => [
                        'id_modal' => 'viewModalUsers',
                        'route_show' => route('admin.users.show', ['user' => ':id']),
                    ],
                ],
            ],
            'table_settings' => [
                'id' => 'users-table',
                'tableAjax' => route('admin.users.index'),
                'columns' => [
                    ['data' => 'id', 'name' => 'id', 'title' => 'ID'],
                    ['data' => 'name', 'name' => 'name', 'title' => 'Nama'],
                    ['data' => 'email', 'name' => 'email', 'title' => 'Email'],
                    ['data' => 'roles', 'name' => 'roles', 'title' => 'Roles'],
                    ['data' => 'permissions', 'name' => 'permissions', 'title' => 'Direct Permissions'],
                    ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Dibuat Pada'],
                    ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false],
                ],
            ],
            'users' => $users,
            'roles' => $roles,
            'all_permissions' => $permissions,
            'permission_groups' => User::getpermissionGroups(),
        ]);
    }

    public function store(UserRequest $request): JsonResponse
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), $request->rules());
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->messages()
                ], 422);
            }

            // Create user with validated data
            $userData = $request->validated();
            $userData['password'] = bcrypt($userData['password']);
            $user = User::create($userData);

            // Assign roles and permissions
            $user->syncRoles($request->roles);

            if ($permissions = $request->input('permissions')) {
                $user->syncPermissions($permissions);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'User successfully created',
                'data' => $user->load('roles', 'permissions')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create user',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function update(UserRequest $request, User $user): JsonResponse
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), $request->rules());
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->messages()
                ], 422);
            }

            // Update user with validated data
            $userData = $request->validated();
            if (isset($userData['password'])) {
                $userData['password'] = bcrypt($userData['password']);
            } else {
                unset($userData['password']);
            }

            $user->update($userData);

            // Sync roles and permissions
            $user->syncRoles($request->roles);

            if ($permissions = $request->input('permissions')) {
                $user->syncPermissions($permissions);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'User successfully updated',
                'data' => $user->load('roles', 'permissions')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(User $user): JsonResponse
    {
        $user->load('roles', 'permissions');

        return response()->json([
            'message' => 'Success',
            'data' => $user
        ]);
    }
}
