<?php
namespace Modules\Video\App\Services\Repositories;

use Modules\Video\App\Repositories\Contract\QuizRepositoryInterface;
use Modules\Video\App\Services\Contract\QuizServiceInterface;

class QuizServiceRepository implements QuizServiceInterface
{
    protected $quizRepository;

    public function __construct(QuizRepositoryInterface $quizRepository)
    {
        $this->quizRepository = $quizRepository;
    }

    public function getAllQuizzes()
    {
        return $this->quizRepository->getAllQuizzes();
    }

    public function getQuizById($id)
    {
        return $this->quizRepository->getQuizById($id);
    }

    public function createQuiz($videoId, array $data)
    {
        $data['video_id'] = $videoId;
        return $this->quizRepository->createQuiz($data);
    }

    public function quizBelongsToVideo($quizId, $videoId)
    {
        return $this->quizRepository->quizBelongsToVideo($quizId, $videoId);
    }

    public function updateQuiz($quizId, $videoId, array $data)
    {
        return $this->quizRepository->updateQuiz($quizId, $videoId, $data);
    }

    public function deleteQuiz($quizId, $videoId)
    {
        return $this->quizRepository->deleteQuiz($quizId, $videoId);
    }


    public function getQuizzesByVideoId($videoId)
    {
        return $this->quizRepository->getQuizzesByVideoId($videoId);
    }

    public function getMaxQuizNumberByVideoId($videoId)
    {
        return $this->quizRepository->getMaxQuizNumberByVideoId($videoId);
    }
}
