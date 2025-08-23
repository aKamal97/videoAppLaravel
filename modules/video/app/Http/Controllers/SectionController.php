<?php

namespace Modules\Video\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Modules\Video\App\Services\Contract\SectionServiceInterface;
use Modules\Video\App\Http\Requests\CreateSectionRequest;
use Modules\Video\App\Transformers\SectionResource;


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
        if($sections->isEmpty()) {
            return $this->success([], 404);
        }

        return $this->success(SectionResource::collection($sections), 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($videoId , CreateSectionRequest $request)
    {
        $data = $request->validated();
        $sections =[];
        try{
            foreach($data['sections'] as $sectionData ){
                $isVideoHasSections = $this->sectionService->getSectionsByVideoId($videoId);
                if($isVideoHasSections->isEmpty()){
                        $sectionData['section_number'] = 1;
                    } else {
                      $lastSectionNumber = $this->sectionService->getMaxSectionNumberByVideoId($videoId);
                        $sectionData['section_number'] = $lastSectionNumber + 1;
                    }
                $section = $this->sectionService->createSection($videoId, $sectionData);
                if(!$section) {
                    return $this->failuer('Section not created', 400);

                }
                else
                {
                    array_push($sections, $section);

                }


            }

            if(empty($sections)) {
                return $this->failuer('Section not created', 400);
            }
            return $this->success(data:  SectionResource::collection($sections), statusCode: 201);
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

    /**
     * Get sections by video ID.
     */
    public function getSections($videoId)
    {
        $sections = $this->sectionService->getSectionsByVideoId($videoId);
        if($sections->isEmpty()) {
            return $this->success([], 404);
        }
        return $this->success(SectionResource::collection($sections), 200);
    }
}
