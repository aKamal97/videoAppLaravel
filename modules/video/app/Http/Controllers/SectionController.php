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
            return $this->success([], 404);
        }

        return $this->success(SectionResource::collection($sections), 200);
    }

    /**
     * store a new resource.
     */
    public function store($videoId, CreateSectionRequest $request)
    {
        if (!is_numeric($videoId)) {
            return $this->failuer('Video ID must be an integer', 400);
        }
        $video = $this->videoService->getById($videoId);
        if (!$video) {
            return $this->failuer('Video not found', 404);
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
                    return $this->failuer('Section not created', 400);
                } else {
                    array_push($sections, $section);
                }
            }

            if (empty($sections)) {
                return $this->failuer('Section not created', 400);
            }
            return $this->success(data: SectionResource::collection($sections), statusCode: 201);
        } catch (\Throwable $e) {
            return $this->failuer($e->getMessage(), 500);
        }
    }



    /**
     * Show the section by video ID.
     */
    public function show($videoId, $sectionId)
    {
        if (!is_numeric($videoId)) {
            return $this->failuer('Video ID must be an integer', 400);
        }
        if (!is_numeric($sectionId)) {
            return $this->failuer('Section ID must be an integer', 400);
        }
        $video = $this->videoService->getById($videoId);
        if (!$video) {
            return $this->failuer('Video not found', 404);
        }
        try {
            $section = $this->sectionService->getSectionByVideoId($videoId, $sectionId);
            if (!$section) {
                return $this->failuer('Section not exist ', 400);
            }
            return $this->success(data: new SectionResource($section), statusCode: 200);
        } catch (\Throwable $e) {
            return $this->failuer($e->getMessage(), 500);
        }
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSection $request, $videoId, $sectionId)
    {
        if (!is_numeric($videoId)) {
            return $this->failuer('Video ID must be an integer', 400);
        }
        if (!is_numeric($sectionId)) {
            return $this->failuer('Section ID must be an integer', 400);
        }
        $data = $request->validated();
        $video = $this->videoService->getById($videoId);
        if (!$video) {
            return $this->failuer('Video not found', 404);
        }
        try {

            $updatedSection = $this->sectionService->updateSection($videoId, $sectionId, $data);
            if (!$updatedSection) {
                return $this->failuer('Section not exist ', 400);
            }

            return $this->success(message: "section updated successfully", data: new  SectionResource($updatedSection), statusCode: 200);
        } catch (\Throwable $e) {
            return $this->failuer($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($videoId, $sectionId)
    {
        if (!is_numeric($videoId)) {
            return $this->failuer('Video ID must be an integer', 400);
        }
        if (!is_numeric($sectionId)) {
            return $this->failuer('Section ID must be an integer', 400);
        }
        $video = $this->videoService->getById($videoId);
        if (!$video) {
            return $this->failuer('Video not found', 404);
        }
        try {
            $deleted = $this->sectionService->deleteSection($videoId, $sectionId);
            if (!$deleted) {
                return $this->failuer('Section not exist ', 400);
            }

            return $this->success(message: 'section deleted successfully',  statusCode: 200);
        } catch (\Throwable $e) {
            return $this->failuer($e->getMessage(), 500);
        }
    }

    /**
     * Get sections by video ID.
     */
    public function getSections($videoId)
    {
        if (!is_numeric($videoId)) {
            return $this->failuer('Video ID must be an integer', 400);
        }
        $video = $this->videoService->getById($videoId);
        if (!$video) {
            return $this->failuer('Video not found', 404);
        }
        $sections = $this->sectionService->getSectionsByVideoId($videoId);
        if ($sections->isEmpty()) {
            return $this->success([], 404);
        }
        return $this->success(SectionResource::collection($sections), 200);
    }
}