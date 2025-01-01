<?php

use App\Http\Controllers\Api\Device\DeviceController as DeviceDeviceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\ApiDashboardController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Device\DeviceController;
use App\Http\Controllers\Map\MapController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Report\ReportController;
use App\Http\Controllers\Sensor\SensorController;
use App\Http\Controllers\Territory\TerritoryController;
use App\Http\Controllers\User\UserController;
use App\Http\Middleware\CheckSession;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {    
    return redirect(route('auth.login.form'));
})->name('home');

Route::middleware([RedirectIfAuthenticated::class])->group(function () {
    Route::get('login', [LoginController::class, 'loginForm'])->name('auth.login.form');
    Route::post('login', [LoginController::class, 'login'])->name('auth.login.submit');
});

Route::middleware([CheckSession::class])->group(function () {
    Route::get('refresh-token', [LoginController::class, 'refreshToken'])->name('auth.refresh-token');
    Route::post('logout', [LoginController::class, 'logout'])->name('auth.logout');

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('devices/list', [DeviceDeviceController::class, 'getDeviceList'])->name('api.device.list');
    Route::get('api/dashboard', [ApiDashboardController::class, 'getDashboard'])->name('api.dashboard');
    
    Route::get('devices', [DeviceController::class, 'index'])->name('device.index');
    Route::get('devices/create', [DeviceController::class, 'create'])->name('device.create');
    Route::get('devices/{id}/edit', [DeviceController::class, 'edit'])->name('device.edit');
    Route::put('devices/{id}/update', [DeviceController::class, 'update'])->name('device.update');
    Route::get('devices/{id}', [DeviceController::class, 'show'])->name('device.show');
    Route::get('sensors/{id}/realtime', [SensorController::class, 'realtime'])->name('sensor.realtime');
    Route::get('sensors/{id}/edit', [SensorController::class, 'edit'])->name('sensor.edit');
    Route::put('sensors/{id}/update', [SensorController::class, 'update'])->name('sensor.update');

    Route::get('maps', [MapController::class, 'index'])->name('map.index');

    Route::get('reports', [ReportController::class, 'index'])->name('report.index');
    Route::get('reports/download/{id}', [ReportController::class, 'download'])->name('report.download');
    Route::get('reports/list', [ReportController::class, 'reportList'])->name('report.reportList');
    Route::get('reports/{id}/create', [ReportController::class, 'create'])->name('report.create');
    Route::post('reports/{id}', [ReportController::class, 'store'])->name('report.store');

    Route::get('users', [UserController::class, 'index'])->name('user.index');
    Route::post('users', [UserController::class, 'store'])->name('user.store');
    Route::post('users/{id}/update', [UserController::class, 'update'])->name('user.update');
    Route::get('users/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::get('users/create', [UserController::class, 'create'])->name('user.create');
    Route::post('users/{id}', [UserController::class, 'destroy'])->name('user.destroy');

    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('territory/district/{city_id}', [TerritoryController::class, 'getDistrict'])->name('map.getDistrict');
    Route::get('territory/subdistrict/{city_id}', [TerritoryController::class, 'getSubdistrict'])->name('map.getSubdistrict');
});
