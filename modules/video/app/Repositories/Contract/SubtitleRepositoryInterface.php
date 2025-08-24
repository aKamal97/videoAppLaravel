<?php

namespace Modules\Video\App\Repositories\Contract;

interface SubtitleRepositoryInterface 
{
    public function getAllSubtitles();
    public function getSubtitleById($id);
    public function createSubtitle(array $data);
    public function updateSubtitle($id, array $data);
    public function deleteSubtitle($id);
    public function getSubtitlesByVideoId($videoId);
    public function getMaxSubtitleNumberByVideoId($videoId);
}
