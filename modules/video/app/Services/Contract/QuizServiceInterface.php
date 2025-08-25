<?php
namespace Modules\Video\App\Services\Contract;

interface QuizServiceInterface {
    public function getAllQuizzes();
    public function getQuizById($id);
    public function createQuiz($videoId, array $data);
    public function quizBelongsToVideo($quizId, $videoId) ;
    public function updateQuiz($quizId, $videoId, array $data);
    public function deleteQuiz($quizId, $videoId);
    public function getQuizzesByVideoId($videoId);
    public function getMaxQuizNumberByVideoId($videoId);
}
