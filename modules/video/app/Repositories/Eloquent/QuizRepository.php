<?php
namespace Modules\Video\App\Repositories\Eloquent;

use Modules\Video\App\Models\VideoQuiz;
use Modules\Video\App\Repositories\Contract\QuizRepositoryInterface;

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

    public function getQuizById($id)
    {
        return $this->quiz->findOrFail($id);
    }

    public function createQuiz(array $data)
    {
        return $this->quiz->create($data);
    }

    public function updateQuiz($id, array $data)
    {
        $quiz = $this->getQuizById($id);
        if ($quiz) {
            $quiz->update($data);
            return $quiz;
        }
        return null;
    }

    public function deleteQuiz($id)
    {
        $quiz = $this->getQuizById($id);
        if ($quiz) {
            $quiz->delete();
            return true;
        }
        return false;
    }

    public function getQuizzesByVideoId($videoId)
    {
        return $this->quiz->where('video_id', $videoId)->get();
    }

    public function getMaxQuizNumberByVideoId($videoId)
    {
        return $this->quiz->where('video_id', $videoId)->max('quize_number');
    }
}
