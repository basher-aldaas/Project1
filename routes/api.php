<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CourseController;
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

//Routes for register and login and logout
Route::controller(AuthController::class)->group(function (){
    Route::post('register','register')->name('user.register');
    Route::post('login','login')->name('user.login');
        Route::group(['middleware' => ['auth:sanctum']],function (){
            Route::post('logout','logout')->name('user.logout');
        });
});

//Routes for course operation
Route::middleware('auth:sanctum')->group(function (){
    Route::prefix('course')->controller(CourseController::class)->group(function (){
        Route::get('show_courses/{$subject_id}','show_courses')->name('course.show')->middleware('can:show.course');
        Route::post('create_course','create_course')->name('create.course')->middleware('can:create.course');
        Route::post('update_course/{$id}','update_course')->name('update.course')->middleware('can:update.course');
        Route::post('delete_course/{$id}','delete_course')->name('delete.course')->middleware('can:delete.course');

    });

});


Route::get('show_courses/{$subject_id}',[CourseController::class,'show_courses']);
