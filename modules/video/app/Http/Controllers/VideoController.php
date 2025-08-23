<?php

namespace Modules\Video\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Video\App\Http\Requests\CreateVideoRequest;
use Modules\Video\App\Services\Contract\VideoServiceInterface;
use Modules\Video\App\Transformers\VideoResource;

class VideoController extends Controller
{
    protected $videoService;

    public function __construct()
    {
        $this->videoService = app(VideoServiceInterface::class);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $videos = $this->videoService->getAll();
        if($videos->isEmpty()) {
            return $this->success([], 404);
        }
        return $this->success(data:VideoResource::collection($this->videoService->getAll()), statusCode: 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(CreateVideoRequest $request)
    {
        $data = $request->validated();
        try{
            $video = $this->videoService->create($data);
            if(!$video) {
                return $this->failuer('Video not created', 400);
            }

        return $this->success(data: new VideoResource($video), statusCode: 201);
        }catch(\Throwable $e) {
            return $this->failuer($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('video::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('video::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}