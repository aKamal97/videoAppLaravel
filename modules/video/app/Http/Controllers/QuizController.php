<?php

namespace Modules\Video\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Video\App\Models\VideoQuiz;
use Modules\Video\App\Http\Requests\CreateVideoQuizRequest;
use Modules\Video\App\Http\Requests\UpdateVideoQuizRequest;
use Modules\Video\App\Services\Contract\QuizServiceInterface;
use Modules\Video\App\Services\Contract\VideoServiceInterface;
use Modules\Video\App\Transformers\QuizResource;

class QuizController extends Controller
{
    private $quizService;
    private $videoService;

    public function __construct()
    {
        $this->quizService = app(QuizServiceInterface::class);
        $this->videoService = app(VideoServiceInterface::class);
    }

    /**
     * Display a listing of quizzes for a video.
     */
    public function index()
    {
        $quizzes = $this->quizService->getAllQuizzes();
        if($quizzes->isEmpty()) {
            return $this->success([], __('No data found'), 404);
        }

        return $this->success(QuizResource::collection($quizzes));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($videoId , CreateVideoQuizRequest $request)
    {
        if (!is_numeric($videoId)) {
            return $this->failuer(__('Video ID must be an integer'), 400);
        }
        try {
            // First validate video exists - this will throw ModelNotFoundException if video not found
            $this->videoService->getById($videoId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->failuer(__('Video not found'), 404);
        }
        $data = $request->validated();
        $quizzes =[];
        if (!is_numeric($videoId)) {
            return $this->failure(__('Video ID must be an integer'), 400);
        }
        try{
            foreach($data['quizzes'] as $quizData ){
                $isVideoHasQuizzes = $this->quizService->getQuizzesByVideoId($videoId);
                if($isVideoHasQuizzes->isEmpty()){
                        $quizData['quize_number'] = 1;
                    } else {
                        $lastQuizNumber = $this->quizService->getMaxQuizNumberByVideoId($videoId);
                            $quizData['quize_number'] = $lastQuizNumber + 1;
                    }
                $quiz = $this->quizService->createQuiz($videoId, $quizData);
                if(!$quiz) {
                    return $this->failure(__('Quiz not created'), 400);
                }
                else
                {
                    array_push($quizzes, $quiz);
                }
            }
            return $this->success(QuizResource::collection(collect($quizzes)), '', 201);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse(__('Video not found'));
        } catch (\Exception $e) {
            return $this->failure(__('An error occurred while creating quizzes: ') . $e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created quiz for a video.
     */
    public function store(Request $request, $id){}

    /**
     * Show a quiz.
     */
    public function show($videoId, $quizId)
    {

        if (!is_numeric($videoId)) {
            return $this->failuer(__('Video ID must be an integer'), 400);
        }
        if (!is_numeric($quizId)) {
            return $this->failuer(__('Quiz ID must be an integer'), 400);
        }
        try {
            // First validate video exists - this will throw ModelNotFoundException if video not found
            $this->videoService->getById($videoId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->failuer(__('Video not found'), 404);
        }
        try {
            $quiz = $this->quizService->getQuizByVideoId($videoId, $quizId);
            return $this->success(new QuizResource($quiz), 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse(__('Quiz not found'));
        } catch (\Throwable $e) {
            return $this->failure($e->getMessage(), 500);
        }
    }


    /**
     * Update a quiz.
     */
    public function edit($id)
    {
        return view('video::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($videoId, $quizId, UpdateVideoQuizRequest $request)
    {
        $data = $request->validated();
      if (!is_numeric($videoId)) {
            return $this->failuer(__('Video ID must be an integer'), 400);
        }
        if (!is_numeric($quizId)) {
            return $this->failuer(__('Quiz ID must be an integer'), 400);
        }


        try {
            $updated = $this->quizService->updateQuiz($videoId, $quizId, $data);

            if (!$updated) {
                return $this->notFoundResponse(__('Quiz not found or does not belong to the given video'));
            }

            return $this->success(new QuizResource($updated));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse(__('Quiz not found'));
        } catch (\Exception $e) {
            return $this->failure(__('An error occurred while updating quiz: ') . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($videoId, $quizId)
    {
         if (!is_numeric($videoId)) {
            return $this->failure(__('Video ID must be an integer'), 400);
        }
        if (!is_numeric($quizId)) {
            return $this->failure(__('Quiz ID must be an integer'), 400);
        }
        try {
            $quiz = $this->quizService->getQuizByVideoId($videoId, $quizId);

            if (!$quiz) {
                return $this->notFoundResponse(__('Quiz not found'));
            }

            $quizzes = $this->quizService->getQuizzesByVideoId($videoId);

            if ($quizzes === null) {
                return $this->notFoundResponse(__('Video not found'));
            }

            $deleted = $this->quizService->deleteQuiz($quizId, $videoId);

            if (!$deleted) {
                return $this->failure(__('Quiz does not belong to the given video'), 400);
            }

            return $this->success([], __('Quiz deleted successfully'));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse(__('Quiz not found'));
        } catch (\Exception $e) {
            return $this->failure(__('An error occurred while deleting quiz: ') . $e->getMessage(), 500);
        }
    }

    /**
     * Get quizzes of specified video
     */
    public function getQuizzes($videoId)
    {
        try {
            // First validate video exists - this will throw ModelNotFoundException if video not found
            $this->videoService->getById($videoId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFoundResponse(__('Video not found'));
        }

        try {
            $quizzes = $this->quizService->getQuizzesByVideoId($videoId);

            if ($quizzes->isEmpty()) {
                return $this->success([], __('No quizzes found for video ') . $videoId, 404);
            }

            return $this->success(QuizResource::collection($quizzes));
        } catch (\Throwable $e) {
            return $this->failure($e->getMessage(), 500);
        }
    }

}
