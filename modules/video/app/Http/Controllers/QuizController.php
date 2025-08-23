<?php

namespace Modules\Video\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Video\App\Models\VideoQuiz;
use Modules\Video\App\Http\Requests\StoreVideoQuizRequest;
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
    public function index(){}

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, $id){}

    /**
     * Store a newly created quiz for a video.
     */
    public function store(Request $request, $id){}

    /**
     * Show a quiz.
     */
    public function show($id)
    {
        return view('video::show');
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
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}

}