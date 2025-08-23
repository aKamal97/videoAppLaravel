<?php
namespace Modules\Video\App\Repositories\Contract;

interface QuizRepositoryInterface {
    public function getAllQuizzes();
    public function getQuizById($id);
    public function createQuiz(array $data);
    public function updateQuiz($id, array $data);
    public function deleteQuiz($id);
    public function getQuizzesByVideoId($videoId);
    public function getMaxQuizNumberByVideoId($videoId);
}
