<?php

namespace Modules\Video\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Modules\Video\App\Services\Contract\SectionServiceInterface;
use Modules\Video\App\Http\Requests\CreateSectionRequest;
use Modules\Video\App\Http\Requests\UpdateSection;
use Modules\Video\App\Transformers\SectionResource;
use Nwidart\Modules\Process\Updater;

class SectionController extends Controller
{
    private $sectionService;
    public function __construct()
    {
        $this->sectionService = app(SectionServiceInterface::class);
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
        $data = $request->validated();
        $sections = [];
        try {
            foreach ($data['sections'] as $sectionData) {
                $isVideoHasSections = $this->sectionService->getSectionsByVideoId($videoId);
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
        try {
            $section = $this->sectionService->getSectionByVideoId($videoId, $sectionId);
            if (!$section) {
                return $this->failuer('Section not found or video not exist ', 400);
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
        $data = $request->validated();

        try {

            $updatedSection = $this->sectionService->updateSection($videoId, $sectionId, $data);
            if (!$updatedSection) {
                return $this->failuer('Section not found or video not exist ', 400);
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
        try {
            $deleted = $this->sectionService->deleteSection($videoId, $sectionId);
            if (!$deleted) {
                return $this->failuer('Section not found or video not exist ', 400);
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
        $sections = $this->sectionService->getSectionsByVideoId($videoId);
        if ($sections->isEmpty()) {
            return $this->success([], 404);
        }
        return $this->success(SectionResource::collection($sections), 200);
    }
}
