<?php
namespace Modules\Video\App\Repositories\Contract;

interface QuizRepositoryInterface {
    public function getAllQuizzes();
    public function getQuizByVideoId($videoId,$quizId);
    public function createQuiz(array $data);
    public function quizBelongsToVideo($quizId, $videoId) ;
    public function updateQuiz($videoId, $quizId, array $data);
    public function deleteQuiz($videoId, $quizId);
    public function getQuizzesByVideoId($videoId);
    public function getMaxQuizNumberByVideoId($videoId);
}
