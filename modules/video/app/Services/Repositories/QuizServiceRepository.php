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

    public function getQuizByVideoId($videoId,$quizId)
    {
        return $this->quizRepository->getQuizByVideoId($videoId,$quizId);
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

    public function updateQuiz($videoId, $quizId, array $data)
    {
        return $this->quizRepository->updateQuiz($videoId, $quizId, $data);
    }

    public function deleteQuiz($videoId, $quizId)
    {
        return $this->quizRepository->deleteQuiz($videoId, $quizId);
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
