<?php

namespace Modules\Video\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Modules\Video\App\Services\Contract\SectionServiceInterface;
use Modules\Video\App\Http\Requests\CreateSectionRequest;
use Modules\Video\App\Http\Requests\UpdateSection;
use Modules\Video\App\Services\Contract\VideoServiceInterface;
use Modules\Video\App\Transformers\SectionResource;
use Nwidart\Modules\Process\Updater;

class SectionController extends Controller
{
    private $sectionService, $videoService;
    public function __construct()
    {
        $this->sectionService = app(SectionServiceInterface::class);
        $this->videoService = app(VideoServiceInterface::class);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = $this->sectionService->getAllSections();
        if ($sections->isEmpty()) {
            return $this->success([], __('No data found'), 404);
        }

        return $this->success(SectionResource::collection($sections));
    }

    /**
     * store a new resource.
     */
    public function store($videoId, CreateSectionRequest $request)
    {
        if (!is_numeric($videoId)) {
            return $this->failure(__('Video ID must be an integer'), 400);
        }
        try {
            // First validate video exists - this will throw ModelNotFoundException if video not found
            $this->videoService->getById($videoId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse(__('Video not found'));
        }
        $data = $request->validated();
        $sections = [];
        try {
            $isVideoHasSections = $this->sectionService->getSectionsByVideoId($videoId);
            foreach ($data['sections'] as $sectionData) {
                if ($isVideoHasSections->isEmpty()) {
                    $sectionData['section_number'] = 1;
                } else {
                    $lastSectionNumber = $this->sectionService->getMaxSectionNumberByVideoId($videoId);
                    $sectionData['section_number'] = $lastSectionNumber + 1;
                }
                $section = $this->sectionService->createSection($videoId, $sectionData);
                if (!$section) {
                    return $this->failure(__('Section not created'), 400);
                } else {
                    array_push($sections, $section);
                }
            }

            if (empty($sections)) {
                return $this->failure(__('Section not created'), 400);
            }
            return $this->success(SectionResource::collection($sections), '', 201);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse(__('Section not exist'));
        } catch (\Throwable $e) {
            return $this->failure($e->getMessage(), 500);
        }
    }



    /**
     * Show the section by video ID.
     */
    public function show($videoId, $sectionId)
    {
        if (!is_numeric($videoId)) {
            return $this->failure(__('Video ID must be an integer'), 400);
        }
        if (!is_numeric($sectionId)) {
            return $this->failure(__('Section ID must be an integer'), 400);
        }
        try {
            // First validate video exists - this will throw ModelNotFoundException if video not found
            $this->videoService->getById($videoId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse(__('Video not found'));
        }
        try {
            $section = $this->sectionService->getSectionByVideoId($videoId, $sectionId);
            return $this->success(new SectionResource($section));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse(__('Section not exist'));
        } catch (\Throwable $e) {
            return $this->failure($e->getMessage(), 500);
        }
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSection $request, $videoId, $sectionId)
    {
        if (!is_numeric($videoId)) {
            return $this->failure(__('Video ID must be an integer'), 400);
        }
        if (!is_numeric($sectionId)) {
            return $this->failure(__('Section ID must be an integer'), 400);
        }
        $data = $request->validated();
        try {
            // First validate video exists - this will throw ModelNotFoundException if video not found
            $this->videoService->getById($videoId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse(__('Video not found'));
        }
        try {

            $updatedSection = $this->sectionService->updateSection($videoId, $sectionId, $data);
            return $this->success(new SectionResource($updatedSection), __("section updated successfully"));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse(__('Section not exist'));
        } catch (\Throwable $e) {
            return $this->failure($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($videoId, $sectionId)
    {
        if (!is_numeric($videoId)) {
            return $this->failure(__('Video ID must be an integer'), 400);
        }
        if (!is_numeric($sectionId)) {
            return $this->failure(__('Section ID must be an integer'), 400);
        }
        try {
            // First validate video exists - this will throw ModelNotFoundException if video not found
            $this->videoService->getById($videoId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse(__('Video not found'));
        }
        try {
            $this->sectionService->deleteSection($videoId, $sectionId);
            return $this->success([], __('section deleted successfully'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse(__('Section not exist'));
        } catch (\Throwable $e) {
            return $this->failure($e->getMessage(), 500);
        }
    }

    /**
     * Get sections by video ID.
     */
    public function getSections($videoId)
    {
        if (!is_numeric($videoId)) {
            return $this->failure(__('Video ID must be an integer'), 400);
        }
        
        try {
            // First validate video exists - this will throw ModelNotFoundException if video not found
            $this->videoService->getById($videoId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse(__('Video not found'));
        }
        
        try {
            $sections = $this->sectionService->getSectionsByVideoId($videoId);
            if ($sections->isEmpty()) {
                return $this->success([], __('No data found'), 404);
            }
            return $this->success(SectionResource::collection($sections));
        } catch (\Throwable $e) {
            return $this->failure($e->getMessage(), 500);
        }
    }
}