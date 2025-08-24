<?php

namespace Modules\Video\App\Services\Contract;

interface SubtitleServiceInterface {
    public function getAllSubtitles();
    public function getSubtitleById($id);
    public function createSubtitle($videoId, array $data);
    public function updateSubtitle($id, array $data);
    public function deleteSubtitle($id);
    public function getSubtitlesByVideoId($videoId);
    public function getMaxSubtitleNumberByVideoId($videoId);
}
