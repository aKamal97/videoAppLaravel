<?php
namespace Modules\Video\App\Services\Contract;

interface QuizServiceInterface {
    public function getAllQuizzes();
    public function getQuizById($id);
    public function createQuiz($videoId, array $data);
    public function updateQuiz($id, array $data);
    public function deleteQuiz($id);
    public function getQuizzesByVideoId($videoId);
    public function getMaxQuizNumberByVideoId($videoId);
}
