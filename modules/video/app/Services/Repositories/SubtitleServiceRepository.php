<?php

namespace Modules\Video\App\Services\Repositories;

use Modules\Video\App\Repositories\Contract\SubtitleRepositoryInterface;
use Modules\Video\App\Services\Contract\SubtitleServiceInterface;

class SubtitleServiceRepository implements SubtitleServiceInterface
{
    protected $subtitleRepository;

    public function __construct(SubtitleRepositoryInterface $subtitleRepository)
    {
        $this->subtitleRepository = $subtitleRepository;
    }

    public function getAllSubtitles()
    {
        return $this->subtitleRepository->getAllSubtitles();
    }

    public function getSubtitleById($id)
    {
        return $this->subtitleRepository->getSubtitleById($id);
    }

    public function createSubtitle($videoId, array $data)
    {
        $data['video_id'] = $videoId;
        return $this->subtitleRepository->createSubtitle($data);
    }

    public function subtitleBelongsToVideo($subtitleId, $videoId)
    {
        return $this->subtitleRepository->subtitleBelongsToVideo($subtitleId, $videoId);
    }

    public function updateSubtitle($subtitleId, $videoId, array $data)
    {
        return $this->subtitleRepository->updateSubtitle($subtitleId, $videoId, $data);
    }

    public function deleteSubtitle($subtitleId, $videoId)
    {
        return $this->subtitleRepository->deleteSubtitle($subtitleId, $videoId);
    }

    public function getSubtitlesByVideoId($videoId)
    {
        return $this->subtitleRepository->getSubtitlesByVideoId($videoId);
    }

    public function getMaxSubtitleNumberByVideoId($videoId)
    {
        return $this->subtitleRepository->getMaxSubtitleNumberByVideoId($videoId);
    }
}

