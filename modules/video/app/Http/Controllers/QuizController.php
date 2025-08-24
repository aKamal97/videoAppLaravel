<?php

namespace Modules\Video\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Video\App\Models\VideoQuiz;
use Modules\Video\App\Http\Requests\CreateVideoQuizRequest;
use Modules\Video\App\Http\Requests\UpdateVideoQuizRequest;
use Modules\Video\App\Services\Contract\QuizServiceInterface;
use Modules\Video\App\Transformers\QuizResource;

class QuizController extends Controller
{
    private $quizService;

    public function __construct()
    {
        $this->quizService = app(QuizServiceInterface::class);
    }

    /**
     * Display a listing of quizzes for a video.
     */
    public function index()
    {
        $quizzes = $this->quizService->getAllQuizzes();
        if($quizzes->isEmpty()) {
            return $this->success([], 404);
        }

        return $this->success(QuizResource::collection($quizzes), 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($videoId , CreateVideoQuizRequest $request)
    {
        $data = $request->validated();
        $quizzes =[];
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
                    return $this->failuer('Quiz not created', 400);
                }
                else
                {
                    array_push($quizzes, $quiz);
                }
            }
            return $this->success(QuizResource::collection(collect($quizzes)), 201);
        } catch (\Exception $e) {
            return $this->failuer('An error occurred while creating quizzes: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created quiz for a video.
     */
    public function store(Request $request, $id){}

    /**
     * Show a quiz.
     */
    public function show($id)
    {
        $quiz = $this->quizService->getQuizById($id);
        if(!$quiz) {
            return $this->failuer('Quiz not found', 404);
        }
        return $this->success(new QuizResource($quiz), 200);
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
    public function update(UpdateVideoQuizRequest $request, $id)
    {
        $data = $request->validated();
        $videoId = $data['video_id'];

        try {
            $quiz = $this->quizService->getQuizById($id);

            if (!$quiz) {
                return $this->failuer('Quiz not found', 404);
            }

            $updated = $this->quizService->updateQuiz($id, $videoId, $data);

            if (!$updated) {
                return $this->failuer('Quiz not found or does not belong to the given video', 404);
            }

            return $this->success(new QuizResource($updated), 200);

        } catch (\Exception $e) {
            return $this->failuer('An error occurred while updating quiz: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($quizId, $videoId) 
    {
        try {
            $quiz = $this->quizService->getQuizById($quizId);

            if (!$quiz) {
                return $this->failuer('Quiz not found', 404);
            }

            $quizzes = $this->quizService->getQuizzesByVideoId($videoId);

            if ($quizzes === null) {
                return $this->failuer('Video not found', 404);
            }

            $deleted = $this->quizService->deleteQuiz($quizId, $videoId);

            if (!$deleted) {
                return $this->failuer('Quiz does not belong to the given video', 400);
            }

            return $this->success("Quiz {$quizId} deleted successfully", 200);

        } catch (\Exception $e) {
            return $this->failuer('An error occurred while deleting quiz: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get quizzes of specified video
     */
    public function getQuizzes($videoId)
    {
        $quizzes = $this->quizService->getQuizzesByVideoId($videoId);

        if ($quizzes === null) {
            return $this->failuer('Video not found', 404);
        }

        if ($quizzes->isEmpty()) {
            return $this->success([], 'No quizzes found for video ' . $videoId, 404);
        }

        return $this->success(QuizResource::collection($quizzes), 200);
    }

}