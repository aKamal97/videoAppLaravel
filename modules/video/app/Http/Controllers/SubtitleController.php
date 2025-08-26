<?php

namespace Modules\Video\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Video\App\Http\Requests\CreateSubtitleRequest;
use Modules\Video\App\Services\Contract\SubtitleServiceInterface;
use Modules\Video\App\Services\Contract\VideoServiceInterface;
use Modules\Video\App\Transformers\SubtitleResource;
use Modules\Video\App\Http\Requests\UpdateSubtitleRequest;


class SubtitleController extends Controller
{
    private $subtitleService;
    private $videoService;

    public function __construct()
    {
        $this->subtitleService = app(SubtitleServiceInterface::class);
        $this->videoService = app(VideoServiceInterface::class);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subtitles = $this->subtitleService->getAllSubtitles();
        if($subtitles->isEmpty()) {
            return $this->success([], __('No data found'), 404);
        }

        return $this->success(SubtitleResource::collection($subtitles));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($videoId , CreateSubtitleRequest $request)
    {
        if (!is_numeric($videoId)) {
            return $this->failure(__('Video ID must be an integer'), 400);
        }
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
                    return $this->failure(__('Subtitle not created'), 400);
                }
                else
                {
                    array_push($subtitles, $subtitle);
                }
            }
            return $this->success(SubtitleResource::collection(collect($subtitles)), '', 201);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse(__('Video not found'));
        } catch (\Exception $e) {
            return $this->failure(__('An error occurred while creating subtitles: ') . $e->getMessage(), 500);
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
        if (!is_numeric($id)) {
            return $this->failure(__('Subtitle ID must be an integer'), 400);
        }
        $subtitle = $this->subtitleService->getSubtitleById($id);
        if(!$subtitle) {
            return $this->notFoundResponse(__('Subtitle not found'));
        }
        return $this->success(new SubtitleResource($subtitle));
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
    public function update(UpdateSubtitleRequest $request, $id)
    {
        $data = $request->validated();
        $videoId = $data['video_id'];
        if (!is_numeric($id)) {
            return $this->failure(__('Subtitle ID must be an integer'), 400);
        }

        try {
            $subtitle = $this->subtitleService->getSubtitleById($id);

            if (!$subtitle) {
                return $this->notFoundResponse(__('Subtitle not found'));
            }

            $updated = $this->subtitleService->updateSubtitle($id, $videoId, $data);

            if (!$updated) {
                return $this->failure(__('Subtitle does not belong to the given video'), 400);
            }

            return $this->success(new SubtitleResource($updated));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse(__('Subtitle not found'));
        } catch (\Exception $e) {
            return $this->failure(__('An error occurred while updating subtitle: ') . $e->getMessage(), 500);
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($subtitleId, $videoId)
    {
        if (!is_numeric($subtitleId)) {
            return $this->failure(__('Subtitle ID must be an integer'), 400);
        }
        if (!is_numeric($videoId)) {
            return $this->failure(__('Video ID must be an integer'), 400);
        }
        try {
            $subtitle = $this->subtitleService->getSubtitleById($subtitleId);

            if (!$subtitle) {
                return $this->notFoundResponse(__('Subtitle not found'));
            }

            $subtitles = $this->subtitleService->getSubtitlesByVideoId($videoId);

            if ($subtitles === null) {
                return $this->notFoundResponse(__('Video not found'));
            }

            $deleted = $this->subtitleService->deleteSubtitle($subtitleId, $videoId);

            if (!$deleted) {
                return $this->failure(__('Subtitle does not belong to the given video'), 400);
            }

            return $this->success([], __('Subtitle deleted successfully'));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse(__('Subtitle not found'));
        } catch (\Exception $e) {
            return $this->failure(__('An error occurred while deleting subtitle: ') . $e->getMessage(), 500);
        }
    }

    public function getSubtitles($videoId)
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
            $subtitles = $this->subtitleService->getSubtitlesByVideoId($videoId);

            if ($subtitles->isEmpty()) {
                return $this->success([], __('No subtitles found for video ') . $videoId, 404);
            }

            return $this->success(SubtitleResource::collection($subtitles));
        } catch (\Throwable $e) {
            return $this->failure($e->getMessage(), 500);
        }
    }



}
