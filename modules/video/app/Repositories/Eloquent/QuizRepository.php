<?php
namespace Modules\Video\App\Repositories\Eloquent;

use Modules\Video\App\Models\VideoQuiz;
use Modules\Video\App\Repositories\Contract\QuizRepositoryInterface;
use Modules\Video\App\Repositories\Contract\VideoRepositoryInterface;

class QuizRepository implements QuizRepositoryInterface
{
    private $quiz;

    public function __construct(VideoQuiz $quiz)
    {
        $this->quiz = $quiz;
    }

    public function getAllQuizzes()
    {
        return $this->quiz->all();
    }

    public function getQuizByVideoId($videoId, $quizId)
    {
        return $this->quiz->where('video_id', $videoId)->where('id', $quizId)->firstOrFail();
    }

    public function createQuiz(array $data)
    {
        return $this->quiz->create($data);
    }

    public function quizBelongsToVideo($quizId, $videoId): bool
    {
        return $this->quiz
            ->where('id', $quizId)
            ->where('video_id', $videoId)
            ->exists();
    }

    public function updateQuiz($videoId, $quizId, array $data)
    {
        $isQuiz = $this->quizBelongsToVideo($quizId, $videoId);

        if (!$isQuiz) {
            return null;
        }

        $quiz = $this->getQuizByVideoId($videoId, $quizId);
        $quiz->update($data);
        return $quiz;
    }

    public function deleteQuiz($videoId, $quizId)
    {
        $isQuiz = $this->quizBelongsToVideo($quizId, $videoId);

        if (!$isQuiz) {
            return false;
        }

        $quiz = $this->getQuizByVideoId($videoId, $quizId);
        $quiz->delete();
        return true;
    }

    public function getQuizzesByVideoId($videoId)
    {
        // Let VideoRepository throw ModelNotFoundException if video not found
        app(VideoRepositoryInterface::class)->getById($videoId);
        
        return $this->quiz->where('video_id', $videoId)->get();
    }

    public function getMaxQuizNumberByVideoId($videoId)
    {
        return $this->quiz->where('video_id', $videoId)->max('quize_number');
    }
}
