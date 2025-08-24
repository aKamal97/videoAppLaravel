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

    public function updateSubtitle($id, array $data)
    {
        return $this->subtitleRepository->updateSubtitle($id, $data);
    }

    public function deleteSubtitle($id)
    {
        return $this->subtitleRepository->deleteSubtitle($id);
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

