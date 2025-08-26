<?php

namespace Modules\Video\App\Services\Contract;

interface SubtitleServiceInterface {
    public function getAllSubtitles();
    public function getSubtitleByVideoId($videoId,$subtitleId);
    public function createSubtitle($videoId, array $data);
    public function subtitleBelongsToVideo($subtitleId, $videoId) ;
    public function updateSubtitle($videoId, $subtitleId, array $data);
    public function deleteSubtitle($videoId, $subtitleId);
    public function getSubtitlesByVideoId($videoId);
    public function getMaxSubtitleNumberByVideoId($videoId);
}
