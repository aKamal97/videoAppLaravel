<?php

namespace Modules\Video\App\Repositories\Contract;

interface SubtitleRepositoryInterface 
{
    public function getAllSubtitles();
    public function getSubtitleById($id);
    public function createSubtitle(array $data);
    public function subtitleBelongsToVideo($subtitleId, $videoId) ;
    public function updateSubtitle($subtitleId, $videoId, array $data);
    public function deleteSubtitle($subtitleId, $videoId);
    public function getSubtitlesByVideoId($videoId);
    public function getMaxSubtitleNumberByVideoId($videoId);
}
