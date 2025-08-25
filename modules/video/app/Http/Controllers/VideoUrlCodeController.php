<?php

namespace Modules\Video\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Video\App\Services\Contract\VideoServiceInterface;
use Modules\Video\App\Services\Contract\VideoUrlCodeServiceInterface;
use Modules\Video\App\Http\Requests\CreateVideoUrlCodeRequest;
use Modules\Video\App\Http\Requests\UpdateVideoUrlCodeRequest;
use Modules\Video\App\Transformers\VideoUrlCodeResource;

class VideoUrlCodeController extends Controller
{
    protected $videoUrlCodeService, $videoService;
    public function __construct()
    {
        $this->videoService = app(VideoServiceInterface::class);
        $this->videoUrlCodeService = app(VideoUrlCodeServiceInterface::class);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!$this->videoUrlCodeService) {
            return $this->failuer(__('Service not available'), 500);
        }
        if (!$this->videoUrlCodeService->getAllUrls() || $this->videoUrlCodeService->getAllUrls()->isEmpty()) {
            return $this->success(__('No data found'), [], 404);
        }
        return $this->success($this->videoUrlCodeService->getAllUrls(), 200);
    }

    /**
     * Display a listing of the resource by video id.
     */
    public function getUrlCodesByVideoId($videoId)
    {
        if (!is_numeric($videoId)) {
            return $this->failuer(__('Video ID must be an integer'), 400);
        }
        if (!$this->videoUrlCodeService) {
            return $this->failuer(__('Service not available'), 500);
        }
        
        try {
            // First validate video exists - this will throw ModelNotFoundException if video not found
            $this->videoService->getById($videoId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->failuer(__('Video not found'), 404);
        }
        
        try {
            $urlCodes = $this->videoUrlCodeService->getUrlCodesByVideoId($videoId);
            if ($urlCodes->isEmpty()) {
                return $this->success(__('No data found'), [], 404);
            }

            return $this->success(VideoUrlCodeResource::collection($urlCodes), 200);
        } catch (\Throwable $e) {
            return $this->failuer($e->getMessage(), 500);
        }
    }
    /**
     * store a new resource.
     */
    public function store($videoId, CreateVideoUrlCodeRequest $request)
    {
        if (!is_numeric($videoId)) {
            return $this->failuer(__('Video ID must be an integer'), 400);
        }
        if (!$this->videoUrlCodeService) {
            return $this->failuer(__('Service not available'), 500);
        }
        
        try {
            // First validate video exists - this will throw ModelNotFoundException if video not found
            $this->videoService->getById($videoId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->failuer(__('Video not found'), 404);
        }
        
        try {
            $data = $request->validated();
            $urlCodes = [];
            $isVideoHasUrls = $this->videoUrlCodeService->getUrlCodesByVideoId($videoId);

            foreach ($data['url_codes'] as $code) {
                if ($isVideoHasUrls->isEmpty()) {
                    $code['url_number'] = 1;
                } else {
                    $lastCodeNumber = $this->videoUrlCodeService->getMaxUrlCodeNumberByVideoId($videoId);
                    $code['url_number'] = $lastCodeNumber + 1;
                }
                $urlCode = $this->videoUrlCodeService->createUrlCode($videoId, $code);
                if (!$urlCode) {
                    return $this->failuer(__('Url code not created'), 400);
                } else {
                    array_push($urlCodes, $urlCode);
                }
            }


            return $this->success(data: VideoUrlCodeResource::collection($urlCodes), statusCode: 201);
        } catch (\Throwable $e) {
            return $this->failuer($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($videoId, $urlCodeId, UpdateVideoUrlCodeRequest $request)
    {
        if (!is_numeric($videoId)) {
            return $this->failuer(__('Video ID must be an integer'), 400);
        }
        if (!is_numeric($urlCodeId)) {
            return $this->failuer(__('Video-Url-Code ID must be an integer'), 400);
        }
        if (!$this->videoUrlCodeService) {
            return $this->failuer(__('Service not available'), 500);
        }
        try {
            // First validate video exists - this will throw ModelNotFoundException if video not found
            $this->videoService->getById($videoId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->failuer(__('Video not found'), 404);
        }
        
        try {
            // Now try to update URL code - this will throw ModelNotFoundException if URL code not found
            $data = $request->validated();
            $updatedUrlCode = $this->videoUrlCodeService->updateUrlCode($videoId, $urlCodeId, $data);
            return $this->success(message: __("Url Code Updated Successfully!"), data: new  VideoUrlCodeResource($updatedUrlCode), statusCode: 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->failuer(__('Url code not exist'), 404);
        } catch (\Throwable $e) {
            return $this->failuer($e->getMessage(), 500);
        }
    }

    /*
    * Remove the specified resource from storage.
    */
    public function destroy($videoId, $urlCodeId)
    {
        if (!is_numeric($videoId)) {
            return $this->failuer(__('Video ID must be an integer'), 400);
        }
        if (!is_numeric($urlCodeId)) {
            return $this->failuer(__('Video-Url-Code ID must be an integer'), 400);
        }
        if (!is_numeric($videoId)) {
            return $this->failuer(__('Video ID must be an integer'), 400);
        }
        if (!is_numeric($urlCodeId)) {
            return $this->failuer(__('Video-Url-Code ID must be an integer'), 400);
        }
        if (!$this->videoUrlCodeService) {
            return $this->failuer(__('Service not available'), 500);
        }
        try {
            // First validate video exists - this will throw ModelNotFoundException if video not found
            $this->videoService->getById($videoId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->failuer(__('Video not found'), 404);
        }
        
        try {
            $this->videoUrlCodeService->deleteUrlCode($videoId, $urlCodeId);
            return $this->success(message: __("Url Code Deleted Successfully!"), statusCode: 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->failuer(__('Url code not exist'), 404);
        } catch (\Throwable $e) {
            return $this->failuer($e->getMessage(), 500);
        }
    }

    /*
    * Show the url code by video ID.
    */
    public function show($videoId, $urlCodeId)
    {
        if (!is_numeric($videoId)) {
            return $this->failuer(__('Video ID must be an integer'), 400);
        }
        if (!is_numeric($urlCodeId)) {
            return $this->failuer(__('Video-Url-Code ID must be an integer'), 400);
        }
        if (!$this->videoUrlCodeService) {
            return $this->failuer(__('Service not available'), 500);
        }
        try {
            // First validate video exists - this will throw ModelNotFoundException if video not found
            $this->videoService->getById($videoId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->failuer(__('Video not found'), 404);
        }
        
        try {
            $urlCode = $this->videoUrlCodeService->getUrlCodeByVideoId($videoId, $urlCodeId);
            return $this->success(data: new VideoUrlCodeResource($urlCode), statusCode: 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->failuer(__('Url code not exist'), 404);
        } catch (\Throwable $e) {
            return $this->failuer($e->getMessage(), 500);
        }
    }
}
