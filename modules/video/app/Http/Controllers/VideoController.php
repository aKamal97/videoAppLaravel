<?php

namespace Modules\Video\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Video\App\Http\Requests\CreateVideoRequest;
use Modules\Video\App\Http\Requests\UpdateVideoRequest;
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
        if ($videos->isEmpty()) {
            return $this->success([], 404);
        }
        return $this->success(data: VideoResource::collection($this->videoService->getAll()), statusCode: 200);
    }

    /**
     * store a new resource.
     */
    public function store(CreateVideoRequest $request)
    {
        $data = $request->validated();
        try {
            $video = $this->videoService->create($data);
            if (!$video) {
                return $this->failuer('Video not created', 400);
            }

            return $this->success(data: new VideoResource($video), statusCode: 201);
        } catch (\Throwable $e) {
            return $this->failuer($e->getMessage(), 500);
        }
    }


    /**
     * Show the specified resource.
     */
    public function show($id)
    {

        try {
            return $this->success(data: new VideoResource($this->videoService->getById($id)), statusCode: 200);
        } catch (\Throwable $e) {
            return $this->failuer($e->getMessage(), 500);
        }
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVideoRequest $request, $id)
    {
        $data = $request->validated();
        try {
            $video = $this->videoService->update($id, $data);
            if (!$video) {
                return $this->failuer('Video not updated', 400);
            }

            return $this->success(data: new VideoResource($video), statusCode: 200);
        } catch (\Throwable $e) {
            return $this->failuer($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $deleted = $this->videoService->delete($id);
            if (!$deleted) {
                return $this->failuer('Video not deleted', 400);
            }

            return $this->success(message: 'video deleted successfully',  statusCode: 200);
        } catch (\Throwable $e) {
            return $this->failuer($e->getMessage(), 500);
        }
    }
}
