<?php

use App\Http\Controllers\Api\InvitationController;
use App\Http\Controllers\Api\UserPermissions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/user-roles', [UserPermissions::class, 'getUserRoles']);
Route::post('/invite', [InvitationController::class, 'invite']);
