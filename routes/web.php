<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Device\DeviceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('login', [LoginController::class, 'loginForm'])->name('auth.login.form');
Route::post('login', [LoginController::class, 'login'])->name('auth.login.submit');
Route::post('logout', [LoginController::class, 'logout'])->name('auth.logout');

Route::get('devices', [DeviceController::class, 'index'])->name('device.index');
Route::get('devices/create', [DeviceController::class, 'create'])->name('device.create');
Route::get('devices/{id}/edit', [DeviceController::class, 'edit'])->name('device.edit');
Route::get('devices/{id}', [DeviceController::class, 'show'])->name('device.show');

