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
            return $this->success([], __('No data found'), 404);
        }
        return $this->success(VideoResource::collection($this->videoService->getAll()));
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
                return $this->failure(__('Video not created'), 400);
            }

            return $this->success(new VideoResource($video), '', 201);
        } catch (\Throwable $e) {
            return $this->failure($e->getMessage(), 500);
        }
    }


    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        if (!is_numeric($id)) {
            return $this->failure(__('Video ID must be an integer'), 400);
        }
        try {
            return $this->success(new VideoResource($this->videoService->getById($id)));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse(__('Video not found'));
        } catch (\Throwable $e) {
            return $this->failure($e->getMessage(), 500);
        }
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVideoRequest $request, $id)
    {
        if (!is_numeric($id)) {
            return $this->failure(__('Video ID must be an integer'), 400);
        }
        $data = $request->validated();
        try {
            $video = $this->videoService->update($id, $data);
            return $this->success(new VideoResource($video));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse(__('Video not found'));
        } catch (\Throwable $e) {
            return $this->failure($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (!is_numeric($id)) {
            return $this->failure(__('Video ID must be an integer'), 400);
        }
        try {
            $this->videoService->delete($id);
            return $this->success([], __('video deleted successfully'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse(__('Video not found'));
        } catch (\Throwable $e) {
            return $this->failure($e->getMessage(), 500);
        }
    }
}
