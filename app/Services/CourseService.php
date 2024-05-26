<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Course_user_pivot;
use App\Models\User;
use App\Models\User_video_pivot;
use Illuminate\Support\Facades\Auth;
use function PHPUnit\Framework\isEmpty;

class CourseService
{
    //done
    public function teacher_courses() :array
    {
        $courses = [];
        $courses_ids = Course_user_pivot::query()->where('user_id', Auth::id())->select('course_id')->get();

        foreach ($courses_ids as $courses_id) {
            $course = Course::query()->where('id', $courses_id->course_id)->first();
            if ($course) {
                $courses[] = $course;
            }
        }
        if (!$courses){
            $message = 'There are no courses for this subject at the moment';
            $code=404;
        }else{
            $message = 'Getting all courses for this course';
            $code=200;
        }
        return [
            'courses' => $courses,
            'message' => $message,
            'code' => $code,

        ];
    }

    //done
    //show all courses for special subject
    public function show_courses($subject_id) :array
    {
            $courses = Course::query()
                ->where('subject_id',$subject_id)
                ->get();
            if ($courses->isEmpty()){
                $message = 'There are no courses for this subject at the moment';
                $code=404;
        }else{
              $message = 'Getting all courses for this course';
                $code=200;
            }
            return [
               'courses' => $courses,
                'message' => $message,
                'code' => $code,

            ];
    }


     public function student_myspace_show_courses()
    {
        $courses = [];
        $course_ids = Course_user_pivot::query()->
        where('user_id' , Auth::id())->
        select('course_id')->distinct()->get();
        foreach ($course_ids as $course_id){
        $course = Course::query()->where('id' , $course_id->course_id)->first();
            if ($course){
                $courses[] = $course;
            }
        }
        if (!$courses){
            $message = 'There are no courses for this subject at the moment';
            $code=404;
        }else{
            $message = 'Getting all courses for this course';
            $code=200;
             }
        return [
            'courses' => $courses,
            'message' => $message,
            'code' => $code,

        ];
    }

    //done
    //create course for special subject with this teacher or any subject and any teacher by admin
    public function create_course($request) : array
    {
        if (Auth::user()->hasRole('teacher') || Auth::user()->hasRole('admin')){
            $course = Course::query()->create([
                'subject_id' => $request['subject_id'],
                'name' => $request['name'],
                'content' => $request['content'],
                'poster' => $request['poster'],
                'hour' => $request['hour'],
                'requirements' => $request['requirements'],
                'price' => $request['price'],
            ]);
            $course_user = Course_user_pivot::query()->create([
                'user_id' => Auth::id(),
                'course_id' => $course->id,
            ]);
            $message = 'Course created successfully';
            $code=200;

        }else{
            $course = [];
            $message = 'you dont have permission for creating a course';
            $code=403;
        }
        return [
            'course' => $course,
            'message' => $message,
            'code' => $code,
        ];
    }

    //update special course for teacher role or any course for admin role
    public function update_course($request,$id) : array
    {

        $course=Course::query()->find($id);
        if (!is_null($course)) {
            $any = Course_user_pivot::query()->where('paid',0)->where('course_id',$id)->first();
            if (!is_null($any)) {
                if ((Auth::user()->hasRole('teacher') && Auth::id() == $any->user_id) || Auth::user()->hasRole('admin')) {
                    Course::query()->find($id)->update([
                        'subject_id' => $request['subject_id'] ?? $course['subject_id'],
                        'name' => $request['name'] ?? $course['name'],
                        'content' => $request['content'] ?? $course['content'],
                        'poster' => $request['poster'] ?? $course['poster'],
                        'hour' => $request['hour'] ?? $course['hour'],
                        'requirements' => $request['requirements'] ?? $course['requirements'],
                        'price' => $request['price'] ?? $course['price'],
                    ]);
                    $course=Course::query()->find($id);
                    $message = 'Updated course successfully';
                    $code=200;
                } else {
                    $course = [];
                    $message = 'you dont have permission for updating this course';
                    $code=403;
                }

            }else{
                $course = [];
                $message = 'This course does not belongs to you to delete it or not found in data';
                $code = 403;
            }
        }
        else{
            $course = [];
            $message = 'This course not found';
            $code=404;
        }

        return [
            'course' => $course,
            'message' => $message,
            'code' => $code,
        ];
    }

    //delete course for special subject with this teacher or any subject and any teacher by admin
    public function delete_course($id) : array
    {
        $course=Course::query()->find($id);
        if (!is_null($course)) {
            $any = Course_user_pivot::query()->where('paid',0)->where('course_id',$id)->first();
            if (!is_null($any)) {
                if (!((Auth::user()->hasRole('teacher') && Auth::id() == $any->user_id)) || Auth::user()->hasRole('admin')) {
                    $course = [];
                    $message = 'you dont have permission for deleting this course';
                    $code = 403;
                } else {

                    $course = $course->delete();
                    $message = 'Deleting course successfully';
                    $code = 200;
                }
            }
            else{
                $course = [];
                $message = 'This course does not belongs to you to delete it or not found in data';
                $code = 403;
            }
        }else{
            $course = [];
            $message = 'This course not found';
            $code=404;
        }

        return [
            'course' => $course,
            'message' => $message,
            'code' => $code,
        ];
    }

    public function paid_course($courseId)
    {

        if (Auth::user()->hasRole('student')){
        $course = Course::query()->where('id' , $courseId)->first();
        $course_user = Course_user_pivot::query()->create([
            'user_id' => Auth::id(),
            'course_id' => $courseId,
            'paid' => 1
        ]);
        $course_price = Course::query()->where('id' , $courseId)->pluck('price')->first();
            $student_wallet = User::query()->where('id' , Auth::id())->pluck('wallet')->first();
            if ($student_wallet > $course_price){
                $student_wallet = $student_wallet - $course_price;
                User::query()->where('id' , Auth::id())->update([
                    'wallet' => $student_wallet
                ]);
                $teacher_id = Course_user_pivot::query()->where('course_id' , $courseId)->
                where('paid' , 0)->pluck('user_id')->first();
                $teacher_wallet = User::query()->where('id' , $teacher_id)->pluck('wallet')->first();
                $teacher_wallet = $teacher_wallet + $course_price;
                User::query()->where('id' , $teacher_id)->update([
                   'wallet' =>  $teacher_wallet
                ]);
            }else{
                $course = [];
                $message = 'you have run out of founds';
            }
        $message = 'course paided success';
        $code = 200;
    }else{
            $course = [];
            $message = 'you are does not have permissions to access';
            $code = 403;
        }
        return [
          'course' => $course,
          'message' => $message,
          'code' => $code,
        ];
    }

}
