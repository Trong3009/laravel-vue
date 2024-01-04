<?php

use App\Http\Controllers\AuthController;

use App\Http\Controllers\TypesVotes\LateVoteController;
use App\Http\Controllers\TypesVotes\OverTimeController;
use App\Http\Controllers\TypesVotes\PauseVoteController;

use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TypesVotes\OnsiteController;
use App\Http\Controllers\TypesVotes\RemoteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserDetailController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('logout', 'logout');
});

Route::middleware('auth:api')->prefix('users')->group(function () {
    Route::get('', [UserController::class, 'getList']);
    Route::post('', [UserController::class, 'create']);
    Route::post('/delete', [UserController::class, 'deleteMultiple']);
    Route::get('/all-user', [UserController::class, 'getAll']);
    Route::put('/lock-and-unlock/{id}', [UserController::class, 'lockAndUnlock']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'delete']);
});

Route::middleware('auth:api')->prefix('userDetails')->group(function () {
    Route::get('', [UserDetailController::class, 'getList']);
    Route::post('', [UserDetailController::class, 'create']);
    Route::post('/delete', [UserDetailController::class, 'deleteMultiple']);
    Route::post('/post-file', [UserDetailController::class, 'createByFile']);
    Route::get('/all-user-detail', [UserDetailController::class, 'getAll']);
    Route::get('/download-excel', [UserDetailController::class, 'getDataExportExcel']);
    Route::put('/{id}', [UserDetailController::class, 'update']);
    Route::delete('/{id}', [UserDetailController::class, 'delete']);
});

//Phiếu xin nghỉ
Route::prefix('pauseVoucher')->group(function () {
    Route::get('', [PauseVoteController::class,'index']);
    Route::post('', [PauseVoteController::class,'store']);
    Route::get('/all-pause-vote', [PauseVoteController::class,'show']);
    Route::put('/{id}', [PauseVoteController::class,'update']);
    Route::delete('/{id}', [PauseVoteController::class, 'destroy']);
});
// Phiếu đi muộn
Route::prefix('lateVoucher')->group(function(){
    Route::get('',[LateVoteController::class, 'index']);
    Route::post('',[LateVoteController::class, 'store']);
    Route::get('/all-late-vote',[LateVoteController::class, 'show']);
    Route::put('/{id}',[LateVoteController::class, 'update']);
    Route::delete('/{id}',[LateVoteController::class,'destroy']);
});
// Phiếu OT
Route::prefix('otVoucher')->group(function(){
    Route::get('',[OverTimeController::class,'index']);
    Route::post('',[OverTimeController::class,'store']);
    Route::get('/all-ot',[OverTimeController::class,'show']);
    Route::put('/{id}',[OverTimeController::class,'update']);
    Route::delete('/{id}',[OverTimeController::class, 'destroy']);
});
//Phiếu remote
Route::prefix('remoteVoucher')->group(function(){
    Route::get('',[RemoteController::class,'index']);
    Route::post('',[RemoteController::class,'store']);
    Route::put('/{id}',[RemoteController::class,'update']);
    Route::delete('/{id}',[RemoteController::class,'destroy']);
});
//Phiếu onsite
Route::prefix('onsiteVoucher')->group(function(){
    Route::get('',[OnsiteController::class,'index']);
    Route::post('',[OnsiteController::class,'store']);
    Route::put('/{id}',[OnsiteController::class,'update']);
    Route::delete('/{id}',[OnsiteController::class, 'destroy']);
});

Route::prefix('roles')->group(function () {
    Route::get('', [RoleController::class, 'view']);
    Route::post('', [RoleController::class, 'create']);
    Route::get('/all-role', [RoleController::class, 'getAll']);
    Route::get('/{id}', [RoleController::class, 'show']);
    Route::put('/{id}', [RoleController::class, 'update']);
    Route::delete('/{id}', [RoleController::class, 'delete']);
});

Route::prefix('permissions')->group(function () {
    Route::get('', [PermissionController::class, 'view']);
});