<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\UserOperationController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\Quiz\AnswerController;
use App\Http\Controllers\Quiz\QuestionController;
use App\Http\Controllers\Quiz\QuizController;
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
    Route::post('user_forgot_password','user_forgot_password')->name('user.user_forgot_password');
    Route::post('user_check_code','user_check_code')->name('user.user_check_code');
    Route::post('user_reset_password','user_reset_password')->name('user.user_reset_password');
    Route::group(['middleware' => ['auth:sanctum']],function (){
            Route::get('logout','logout')->name('user.logout')->name('user.logout');
            Route::post('Accept_teacher_coming_by_email/{id}','Accept_teacher_coming_by_email')->name('Accept.teacher')->middleware('can:add.teacher');
            Route::post('admin_adding_new_teacher','admin_adding_new_teacher')->name('admin.adding.new.teacher')->middleware('can:add.teacher');
        });
});

//Routes for course operation
Route::middleware('auth:sanctum')->group(function (){
    Route::prefix('course')->controller(CourseController::class)->group(function (){
        Route::get('show_courses/{subject_id}','show_courses')->name('course.show')->middleware('can:show.course');
        Route::post('create_course','create_course')->name('create.course')->middleware('can:create.course');
        Route::post('update_course/{id}','update_course')->name('update.course')->middleware('can:update.course');
        Route::post('delete_course/{id}','delete_course')->name('delete.course')->middleware('can:delete.course');

    });

});

//Routes for users operation (admin,teachers,students)
Route::middleware('auth:sanctum')->group(function (){
    Route::controller(UserOperationController::class)->group(function (){
        Route::get('show_students','show_students')->name('students.show')->middleware('can:show.students');//بدي اكد على ه
        Route::get('show_teachers','show_teachers')->name('teachers.show')->middleware('can:show.teachers');
        Route::get('delete_student/{id}','delete_student')->name('delete.student')->middleware('can:delete.student');
        Route::get('delete_teacher/{id}','delete_teacher')->name('delete.teacher')->middleware('can:delete.teacher');
        Route::post('update_profile','update_profile')->name('update.profile');
    });
});


//Routes for quizzes and questions and answers
Route::middleware('auth:sanctum')->group(function (){

    //Routes for quizzes
    Route::controller(QuizController::class)->group(function (){
        Route::get('create_quiz/{course_id}','create_quiz')->name('create.quiz')->middleware('can:create.quiz');
        Route::get('delete_quiz/{quiz_id}','delete_quiz')->name('delete.quiz')->middleware('can:delete.quiz');
        Route::get('show_quizzes','show_quizzes')->name('show.quiz')->middleware('can:show.quiz');

    });

    //Routes for questions
    Route::controller(QuestionController::class)->group(function (){
        Route::post('create_question/{quiz_id}','create_question')->name('create.question')->middleware('can:create.quiz');
        Route::post('update_question/{question_id}','update_question')->name('update.question')->middleware('can:update.quiz');
        Route::get('delete_question/{question_id}','delete_question')->name('delete.question')->middleware('can:delete.quiz');
        Route::get('sh','sh')->name('delete.question')->middleware('can:show.quiz');


    });

    //Routes for answers
    Route::controller(AnswerController::class)->group(function (){
        Route::post('create_answer/{question_id}','create_answer')->name('create.answer')->middleware('can:create.quiz');
        Route::get('delete_answer/{answer_id}','delete_answer')->name('delete.answer')->middleware('can:delete.quiz');
        Route::post('update_answer/{answer_id}','update_answer')->name('update.answer')->middleware('can:update.quiz');

    });
});



Route::get('sh',[QuestionController::class,'sh'])->middleware('auth:sanctum');
