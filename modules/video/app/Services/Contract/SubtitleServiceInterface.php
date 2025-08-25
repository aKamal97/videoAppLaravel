<?php

namespace Modules\Video\App\Services\Contract;

interface SubtitleServiceInterface {
    public function getAllSubtitles();
    public function getSubtitleById($id);
    public function createSubtitle($videoId, array $data);
    public function subtitleBelongsToVideo($subtitleId, $videoId) ;
    public function updateSubtitle($subtitleId, $videoId, array $data);
    public function deleteSubtitle($subtitleId, $videoId);
    public function getSubtitlesByVideoId($videoId);
    public function getMaxSubtitleNumberByVideoId($videoId);
}
