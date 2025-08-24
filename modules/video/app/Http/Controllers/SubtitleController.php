<?php

namespace Modules\Video\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Video\App\Http\Requests\CreateSubtitleRequest;
use Modules\Video\App\Services\Contract\SubtitleServiceInterface;
use Modules\Video\App\Transformers\SubtitleResource;


class SubtitleController extends Controller
{
    private $subtitleService;

    public function __construct()
    {
        $this->subtitleService = app(SubtitleServiceInterface::class);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subtitles = $this->subtitleService->getAllSubtitles();
        if($subtitles->isEmpty()) {
            return $this->success([], 404);
        }

        return $this->success(SubtitleResource::collection($subtitles), 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($videoId , CreateSubtitleRequest $request)
    {
        $data = $request->validated();
        $subtitles =[];
        try{
            foreach($data['subtitles'] as $subtitleData ){
                $isVideoHasSubtitles = $this->subtitleService->getSubtitlesByVideoId($videoId);
                if($isVideoHasSubtitles->isEmpty()){
                        $subtitleData['subtitle_number'] = 1;
                    } else {
                        $lastSubtitleNumber = $this->subtitleService->getMaxSubtitleNumberByVideoId($videoId);
                            $subtitleData['subtitle_number'] = $lastSubtitleNumber + 1;
                    }
                $subtitle = $this->subtitleService->createSubtitle($videoId, $subtitleData);
                if(!$subtitle) {
                    return $this->failuer('Subtitle not created', 400);
                }
                else
                {
                    array_push($subtitles, $subtitle);
                }
            }
            return $this->success(SubtitleResource::collection(collect($subtitles)), 201);
        } catch (\Exception $e) {
            return $this->failuer('An error occurred while creating subtitles: ' . $e->getMessage(), 500);
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
}
