<?php

namespace App\Http\Controllers;

use App\Events\WelcomeEvent;
use App\Http\Requests\Course\CreateCourseRequest;
use App\Http\Requests\Course\UpdateCourseRequest;
use App\Http\Responses\Response;
use App\Models\Course;
use App\Models\Course_user_pivot;
use App\Models\User;
use App\Services\CourseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Spatie\Permission\Models\Role;

class CourseController extends Controller
{
    private  CourseService $courseService;

    public function __construct(CourseService $courseService){
        $this->courseService = $courseService;
    }

    //api to show all course for special subject to all roles
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
            $data = $this->courseService->create_course($request->validated());
            return Response::Success($data['course'],$data['message'],$data['code']);

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
            $data = $this->courseService->update_course($request->validated(),$id);
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








}
