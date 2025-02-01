<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [UserController::class, 'userlist'])->name('userList');
Route::get('getUserData', [UserController::class, 'getUserData'])->name('getUserData');
Route::get('/user-point-recalculate', [UserController::class, 'recalculate'])->name('user.point.recalculate');
