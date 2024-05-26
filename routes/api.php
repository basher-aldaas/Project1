<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\VideoController;
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
        Route::get('show_courses/{subject_id}','show_courses')->name('course.show')->middleware('can:show.course');
        Route::get('teacher_courses' , 'teacher_show_courses')->name('teacher.course');
        Route::get('myspace_courses' , 'myspace_course')->name('myspace.course');
        Route::post('create_course','create_course')->name('create.course')->middleware('can:create.course');
        Route::post('update_course/{id}','update_course')->name('update.course')->middleware('can:update.course');
        Route::delete('delete_course/{id}','delete_course')->name('delete.course')->middleware('can:delete.course');
        Route::post('paid_course/{id}','paid_course')->name('paid.course');


    });

});


//Routes for video operation
Route::middleware('auth:sanctum')->group(function (){
    Route::prefix('video')->controller(VideoController::class)->group(function (){
        Route::get('show_videos/{course_id}','show_videos')->name('show.video')->middleware('can:show.video');
        Route::post('create_video','create_video')->name('create.video')->middleware('can:create.video');
        Route::post('update_video/{id}','update_video')->name('update.video')->middleware('can:update.video');
        Route::post('delete_video/{id}','delete_video')->name('delete.video')->middleware('can:delete.video');

    });

});

//show : category section subjects
Route::middleware('auth:sanctum')->group(function(){
    Route::controller(SubjectController::class)->group(function(){
        Route::get('section' , 'show_sections');
        Route::get('category/{section_id}' , 'show_categories');
        Route::get('subject/{category_id}' , 'show_subjects');
    });
});
