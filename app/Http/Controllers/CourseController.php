<?php

namespace App\Http\Controllers;

use App\Http\Requests\Course\CreateCourseRequest;
use App\Http\Requests\Course\UpdateCourseRequest;
use App\Http\Responses\Response;
use App\Services\CourseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Throwable;

class CourseController extends Controller
{
    private  CourseService $courseService;

    public function __construct(CourseService $courseService){
        $this->courseService = $courseService;
    }

    //api to show all course for special subject to all roles
    public function teacher_show_courses() : jsonResponse
    {
        $data = [];
        try {

            $data = $this->courseService->teacher_courses();
            return Response::Success($data['courses'],$data['message'],$data['code']);

        }catch (Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }
    }
    public function show_courses($subject_id) : jsonResponse
    {
        $data = [];
        try {

            $data = $this->courseService->show_courses($subject_id);
            return Response::Success($data['courses'],$data['message'],$data['code']);

        }catch (Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }
    }

    //api to create course for special subject by this teacher or any subject and any course by admin
    public function create_course(CreateCourseRequest $request) : jsonResponse
    {
        $data = [];
        try {
            if (Auth::user()->hasRole('teacher') || Auth::user()->hasRole('admin')) {

                $imagePath = $request->file('poster')->store('images' , 'public');
                $imageUrl = Storage::url($imagePath);
                $validatedData = $request->validated();
                $validatedData['poster'] = $imageUrl;
                $data = $this->courseService->create_course($validatedData);
                return Response::Success($data['course'], $data['message'], $data['code']);
            }
            else {
                    return response()->json(['unauthorized']);
                }
        }catch (Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }
    }

    //api to update course for special subject by this teacher or any subject and any course by admin
    public function update_course(UpdateCourseRequest $request,$id) : jsonResponse
    {
        $data = [];
        try {
            $imagePath = $request->file('poster')->store('images' , 'public');
            $imageUrl = Storage::url($imagePath);
            $validatedData = $request->validated();
            $validatedData['poster'] = $imageUrl;
            $data = $this->courseService->update_course($validatedData,$id);
            return Response::Success($data['course'],$data['message'],$data['code']);

        }catch (Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }
    }

    //api to delete course for special subject by this teacher or any subject and any course by admin
    public function delete_course($id) : jsonResponse
    {
        $data = [];
        try {
            $data = $this->courseService->delete_course($id);
            return Response::Success($data['course'],$data['message'],$data['code']);

        }catch (Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }
    }

    public function myspace_course()
    {
        $data = [];
        try {
            $data = $this->courseService->student_myspace_show_courses();
            return Response::Success($data['courses'],$data['message'],$data['code']);
        }catch (Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }
    }

}
