<?php

use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\RouteAccessController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('admin')->group(function () {

    Route::controller(MenuController::class)->group(function () {
        Route::get('menus', 'index')->name('admin.menus.index');
        Route::get('menus/{menu}', 'show')->name('admin.menus.show');
        Route::post('menus', 'store')->name('admin.menus.store');
        Route::put('menus/{menu}', 'update')->name('admin.menus.update');
        Route::delete('menus/{menu}', 'destroy')->name('admin.menus.destroy');
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('users', 'index')->name('admin.users.index');
        Route::get('users/{user}', 'show')->name('admin.users.show');
        Route::post('users', 'store')->name('admin.users.store');
        Route::put('users/{user}', 'update')->name('admin.users.update');
        Route::delete('users/{user}', 'destroy')->name('admin.users.destroy');
    });

    Route::controller(RoleController::class)->group(function () {
        Route::get('roles', 'index')->name('admin.roles.index');
        Route::get('roles/{role}', 'show')->name('admin.roles.show');
        Route::post('roles', 'store')->name('admin.roles.store');
        Route::put('roles/{role}', 'update')->name('admin.roles.update');
        Route::delete('roles/{role}', 'destroy')->name('admin.roles.destroy');
    });
    Route::controller(PermissionController::class)->group(function () {
        Route::get('permissions', 'index')->name('admin.permissions.index');
        Route::get('permissions/{permission}', 'show')->name('admin.permissions.show');
        Route::post('permissions', 'store')->name('admin.permissions.store');
        Route::put('permissions/{permission}', 'update')->name('admin.permissions.update');
        Route::delete('permissions/{permission}', 'destroy')->name('admin.permissions.destroy');
    });

    route::controller(RouteAccessController::class)->group(function () {
        Route::get('route-accesses', 'index')->name('admin.route-accesses.index');
        Route::get('route-accesses/{routeAccess}', 'show')->name('admin.route-accesses.show');
        Route::post('route-accesses', 'store')->name('admin.route-accesses.store');
        Route::put('route-accesses/{routeAccess}', 'update')->name('admin.route-accesses.update');
        Route::delete('route-accesses/{routeAccess}', 'destroy')->name('admin.route-accesses.destroy');
    });
});
