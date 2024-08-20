<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::get('/users', [\App\Http\Controllers\UserController::class, 'index']);
//Route::get('/users/{id}', [\App\Http\Controllers\UserController::class, 'user']);

Route::get('/classes', [\App\Http\Controllers\ClassController::class, 'index']);
Route::post('/classes/create', [\App\Http\Controllers\ClassController::class, 'createClass']);

Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('/student/register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::get('/students', [\App\Http\Controllers\UserController::class, 'students']);
Route::get('/student/{stdId}/classes', [\App\Http\Controllers\ClassController::class, 'studentClasses']);
Route::get('/student/{stdId}/home-works', [\App\Http\Controllers\ClassController::class, 'getHomeWorks']);

Route::get('/class/teacher/{stdId}', [\App\Http\Controllers\ClassController::class, 'getTeacher']);


Route::get('/teachers', [\App\Http\Controllers\UserController::class, 'teachers']);
Route::delete('/teachers/{id}', [\App\Http\Controllers\UserController::class, 'teachersDelete']);
Route::post('teachers/register', [\App\Http\Controllers\AuthController::class, 'teacherRegister']);
Route::post('teachers/login', [\App\Http\Controllers\AuthController::class, 'teacherLogin']);
Route::get('teacher/{id}/classes', [\App\Http\Controllers\ClassController::class, 'getTeachersClasses']);

Route::get('/terms', [\App\Http\Controllers\ClassController::class, 'terms']);
Route::post('/terms/create', [\App\Http\Controllers\ClassController::class, 'createTerm']);

Route::middleware('auth.jwt')->group(function () {
    Route::get('/users', [\App\Http\Controllers\UserController::class, 'index']);
    Route::get('/users/{id}', [\App\Http\Controllers\UserController::class, 'user']);
    Route::post('logout', [AuthController::class, 'logout']);
});
