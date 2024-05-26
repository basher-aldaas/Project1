<?php

namespace App\Http\Controllers;

use App\Http\Requests\Video\AddVideoRequest;
use App\Http\Responses\Response;
use App\Models\Course;
use App\Services\VideoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public $videoService;

    public function __construct(VideoService $videoService)
    {
        $this->videoService = $videoService;
    }
    public function create_video(AddVideoRequest $request)
    {
        $video = [];
        try {
            if (Auth::user()->hasRole('teacher') ||  Auth::user()->hasRole('admin')){
            $videoPath = $request->file('url')->store('videos','public');
            $videoUrl = Storage::url($videoPath);
            $validatedData = $request->validated();
            $validatedData['url'] = $videoUrl;
            $video = $this->videoService->addVideos($validatedData);
            return Response::Success($video['video'] , $video['message']);}
            else {
                return ['message' => 'unauthorized'];
            }
        }catch(\Throwable $th){
            $message = $th->getMessage();
            return Response::Error([],$message);
        }
    }
}
