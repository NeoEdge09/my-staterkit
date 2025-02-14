<?php

namespace App\Http\Middleware;

use App\Models\RouteAccess;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

class DynamicRoleAndPermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $routeName = Route::currentRouteName();

        $routeAccesses = RouteAccess::with(['role', 'permission'])
            ->where('route_name', $routeName)
            ->get();

        if ($routeAccesses->isNotEmpty()) {
            $isAuthorized = false;

            foreach ($routeAccesses as $routeAccess) {
                $role = $routeAccess->role;
                $permission = $routeAccess->permission;

                if (($role && $request->user()->hasRole($role->name)) ||
                    ($permission && $request->user()->hasPermissionTo($permission->name))
                ) {
                    $isAuthorized = true;
                    break;
                }
            }

            if ($isAuthorized) {
                return $next($request);
            }

            $roles = $routeAccesses->pluck('role.name')->filter()->all();
            $permissions = $routeAccesses->pluck('permission.name')->filter()->all();

            throw UnauthorizedException::forRoles($roles, $permissions);
        }

        throw UnauthorizedException::forRolesOrPermissions([], []);
    }
}
