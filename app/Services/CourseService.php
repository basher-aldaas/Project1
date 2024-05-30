<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Course_user_pivot;
use Illuminate\Support\Facades\Auth;

class CourseService
{
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
                //'valuation' => $request['valuation']
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


}
