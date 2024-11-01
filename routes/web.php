<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Device\DeviceController;
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
    Route::post('logout', [LoginController::class, 'logout'])->name('auth.logout');
    
    Route::get('devices', [DeviceController::class, 'index'])->name('device.index');
    Route::get('devices/create', [DeviceController::class, 'create'])->name('device.create');
    Route::get('devices/{id}/edit', [DeviceController::class, 'edit'])->name('device.edit');
    Route::get('devices/{id}', [DeviceController::class, 'show'])->name('device.show');
});
