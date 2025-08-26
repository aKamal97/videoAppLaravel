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
            return $this->failure(__('Service not available'), 500);
        }
        if (!$this->videoUrlCodeService->getAllUrls() || $this->videoUrlCodeService->getAllUrls()->isEmpty()) {
            return $this->success([], __('No data found'), 404);
        }
        return $this->success($this->videoUrlCodeService->getAllUrls());
    }

    /**
     * Display a listing of the resource by video id.
     */
    public function getUrlCodesByVideoId($videoId)
    {
        if (!is_numeric($videoId)) {
            return $this->failure(__('Video ID must be an integer'), 400);
        }
        if (!$this->videoUrlCodeService) {
            return $this->failure(__('Service not available'), 500);
        }

        try {
            // First validate video exists - this will throw ModelNotFoundException if video not found
            $this->videoService->getById($videoId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse(__('Video not found'));
        }

        try {
            $urlCodes = $this->videoUrlCodeService->getUrlCodesByVideoId($videoId);
            if ($urlCodes->isEmpty()) {
                return $this->success([], __('No data found'), 404);
            }

            return $this->success(VideoUrlCodeResource::collection($urlCodes));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse(__('Video not found'));
        } catch (\Throwable $e) {
            return $this->failure($e->getMessage(), 500);
        }
    }
    /**
     * store a new resource.
     */
    public function store($videoId, CreateVideoUrlCodeRequest $request)
    {
        if (!is_numeric($videoId)) {
            return $this->failure(__('Video ID must be an integer'), 400);
        }
        if (!$this->videoUrlCodeService) {
            return $this->failure(__('Service not available'), 500);
        }

        try {
            // First validate video exists - this will throw ModelNotFoundException if video not found
            $this->videoService->getById($videoId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse(__('Video not found'));
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
                    return $this->failure(__('Url code not created'), 400);
                } else {
                    array_push($urlCodes, $urlCode);
                }
            }


            return $this->success(VideoUrlCodeResource::collection($urlCodes), '', 201);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse(__('Video not found'));
        } catch (\Throwable $e) {
            return $this->failure($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($videoId, $urlCodeId, UpdateVideoUrlCodeRequest $request)
    {
        if (!is_numeric($videoId)) {
            return $this->failure(__('Video ID must be an integer'), 400);
        }
        if (!is_numeric($urlCodeId)) {
            return $this->failure(__('Video-Url-Code ID must be an integer'), 400);
        }
        if (!$this->videoUrlCodeService) {
            return $this->failure(__('Service not available'), 500);
        }
        try {
            // First validate video exists - this will throw ModelNotFoundException if video not found
            $this->videoService->getById($videoId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse(__('Video not found'));
        }

        try {
            // Now try to update URL code - this will throw ModelNotFoundException if URL code not found
            $data = $request->validated();
            $updatedUrlCode = $this->videoUrlCodeService->updateUrlCode($videoId, $urlCodeId, $data);
            return $this->success(new VideoUrlCodeResource($updatedUrlCode), __("Url Code Updated Successfully!"));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse(__('Url code not exist'));
        } catch (\Throwable $e) {
            return $this->failure($e->getMessage(), 500);
        }
    }

    /*
    * Remove the specified resource from storage.
    */
    public function destroy($videoId, $urlCodeId)
    {
        if (!is_numeric($videoId)) {
            return $this->failure(__('Video ID must be an integer'), 400);
        }
        if (!is_numeric($urlCodeId)) {
            return $this->failure(__('Video-Url-Code ID must be an integer'), 400);
        }
        if (!$this->videoUrlCodeService) {
            return $this->failure(__('Service not available'), 500);
        }
        try {
            // First validate video exists - this will throw ModelNotFoundException if video not found
            $this->videoService->getById($videoId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse(__('Video not found'));
        }

        try {
            $this->videoUrlCodeService->deleteUrlCode($videoId, $urlCodeId);
            return $this->success([], __("Url Code Deleted Successfully!"));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse(__('Url code not exist'));
        } catch (\Throwable $e) {
            return $this->failure($e->getMessage(), 500);
        }
    }

    /*
    * Show the url code by video ID.
    */
    public function show($videoId, $urlCodeId)
    {
        if (!is_numeric($videoId)) {
            return $this->failure(__('Video ID must be an integer'), 400);
        }
        if (!is_numeric($urlCodeId)) {
            return $this->failure(__('Video-Url-Code ID must be an integer'), 400);
        }
        if (!$this->videoUrlCodeService) {
            return $this->failure(__('Service not available'), 500);
        }
        try {
            // First validate video exists - this will throw ModelNotFoundException if video not found
            $this->videoService->getById($videoId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse(__('Video not found'));
        }

        try {
            $urlCode = $this->videoUrlCodeService->getUrlCodeByVideoId($videoId, $urlCodeId);
            return $this->success(new VideoUrlCodeResource($urlCode));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse(__('Url code not exist'));
        } catch (\Throwable $e) {
            return $this->failure($e->getMessage(), 500);
        }
    }
}
