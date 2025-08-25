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
            return $this->failuer('Service not available', 500);
        }
        if (!$this->videoUrlCodeService->getAllUrls() || $this->videoUrlCodeService->getAllUrls()->isEmpty()) {
            return $this->success('No data found', [], 404);
        }
        return $this->success($this->videoUrlCodeService->getAllUrls(), 200);
    }

    /**
     * Display a listing of the resource by video id.
     */
    public function getUrlCodesByVideoId($videoId)
    {
        if (!is_numeric($videoId)) {
            return $this->failuer('Video ID must be an integer', 400);
        }
        if (!$this->videoUrlCodeService) {
            return $this->failuer('Service not available', 500);
        }
        $video = $this->videoService->getById($videoId);
        if (!$video) {
            return $this->failuer('Video not found', 404);
        }
        if (!$this->videoUrlCodeService->getUrlCodesByVideoId($videoId) || $this->videoUrlCodeService->getUrlCodesByVideoId($videoId)->isEmpty()) {
            return $this->success('No data found', [], 404);
        }

        return $this->success(VideoUrlCodeResource::collection($this->videoUrlCodeService->getUrlCodesByVideoId($videoId)), 200);
    }
    /**
     * store a new resource.
     */
    public function store($videoId, CreateVideoUrlCodeRequest $request)
    {
        if (!is_numeric($videoId)) {
            return $this->failuer('Video ID must be an integer', 400);
        }
        if (!$this->videoUrlCodeService) {
            return $this->failuer('Service not available', 500);
        }
        $video = $this->videoService->getById($videoId);
        if (!$video) {
            return $this->failuer('Video not found', 404);
        }
        $data = $request->validated();
        try {
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
                    return $this->failuer('Url code not created', 400);
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
            return $this->failuer('Video ID must be an integer', 400);
        }
        if (!is_numeric($urlCodeId)) {
            return $this->failuer('Video-Url-Code ID must be an integer', 400);
        }
        if (!$this->videoUrlCodeService) {
            return $this->failuer('Service not available', 500);
        }
        $video = $this->videoService->getById($videoId);
        if (!$video) {
            return $this->failuer('Video not found', 404);
        }
        $data = $request->validated();
        try {
            $updatedUrlCode = $this->videoUrlCodeService->updateUrlCode($videoId, $urlCodeId, $data);
            if (!$updatedUrlCode) {
                return $this->failuer('Url code not exist', 400);
            }


            return $this->success(message: "Url Code Updated Successfully!", data: new  VideoUrlCodeResource($updatedUrlCode), statusCode: 200);
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
            return $this->failuer('Video ID must be an integer', 400);
        }
        if (!is_numeric($urlCodeId)) {
            return $this->failuer('Video-Url-Code ID must be an integer', 400);
        }
        if (!is_numeric($videoId)) {
            return $this->failuer('Video ID must be an integer', 400);
        }
        if (!is_numeric($urlCodeId)) {
            return $this->failuer('Video-Url-Code ID must be an integer', 400);
        }
        if (!$this->videoUrlCodeService) {
            return $this->failuer('Service not available', 500);
        }
        $video = $this->videoService->getById($videoId);
        if (!$video) {
            return $this->failuer('Video not found', 404);
        }
        try {
            $isDeleted = $this->videoUrlCodeService->deleteUrlCode($videoId, $urlCodeId);
            if (!$isDeleted) {
                return $this->failuer('Url code not exist', 400);
            }
            return $this->success(message: "Url Code Deleted Successfully!", statusCode: 200);
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
            return $this->failuer('Video ID must be an integer', 400);
        }
        if (!is_numeric($urlCodeId)) {
            return $this->failuer('Video-Url-Code ID must be an integer', 400);
        }
        if (!$this->videoUrlCodeService) {
            return $this->failuer('Service not available', 500);
        }
        $video = $this->videoService->getById($videoId);
        if (!$video) {
            return $this->failuer('Video not found', 404);
        }
        try {
            $urlCode = $this->videoUrlCodeService->getUrlCodeByVideoId($videoId, $urlCodeId);
            if (!$urlCode) {
                return $this->failuer('Url code not exist ', 400);
            }
            return $this->success(data: new VideoUrlCodeResource($urlCode), statusCode: 200);
        } catch (\Throwable $e) {
            return $this->failuer($e->getMessage(), 500);
        }
    }
}
