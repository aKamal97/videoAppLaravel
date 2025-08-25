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
            return $this->success(__('No data found'),[], 404);
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
                return $this->failuer(__('Video not created'), 400);
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
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->failuer(__('Video not found'), 404);
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
            return $this->success(data: new VideoResource($video), statusCode: 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->failuer(__('Video not found'), 404);
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
            $this->videoService->delete($id);
            return $this->success(message: __('video deleted successfully'),  statusCode: 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->failuer(__('Video not found'), 404);
        } catch (\Throwable $e) {
            return $this->failuer($e->getMessage(), 500);
        }
    }
}
