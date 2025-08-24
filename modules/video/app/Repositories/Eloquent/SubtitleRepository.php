<?php

namespace Modules\Video\App\Repositories\Eloquent;

use Modules\Video\App\Models\VideoSubtitles;
use Modules\Video\App\Repositories\Contract\SubtitleRepositoryInterface;
use Modules\Video\App\Repositories\Contract\VideoRepositoryInterface;


class SubtitleRepository implements SubtitleRepositoryInterface
{
    private $subtitle;

    public function __construct(VideoSubtitles $subtitle)
    {
        $this->subtitle = $subtitle;
    }

    public function getAllSubtitles()
    {
        return $this->subtitle->all();
    }

    public function getSubtitleById($id)
    {
        return $this->subtitle->find($id);
    }

    public function createSubtitle(array $data)
    {
        return $this->subtitle->create($data);
    }

    public function subtitleBelongsToVideo($subtitleId, $videoId): bool
    {
        return $this->subtitle
            ->where('id', $subtitleId)
            ->where('video_id', $videoId)
            ->exists();
    }

    public function updateSubtitle($subtitleId, $videoId, array $data)
    {
        $isSubtitle = $this->subtitleBelongsToVideo($subtitleId, $videoId);

        if ($isSubtitle) {
            $subtitle = $this->getSubtitleById($subtitleId);
            $subtitle->update($data);
            return $subtitle;
        }

        return null;
    }

    public function deleteSubtitle($subtitleId, $videoId)
    {
        $isSubtitle = $this->subtitleBelongsToVideo($subtitleId, $videoId);

        if ($isSubtitle) {
            $subtitle = $this->getSubtitleById($subtitleId);
            $subtitle->delete();
            return true;
        }
        return false;
    }

    public function getSubtitlesByVideoId($videoId)
    {
        $video = app(VideoRepositoryInterface::class)->getById($videoId);

        if (!$video) {
            return null; 
        }

        return $this->subtitle->where('video_id', $videoId)->get();
    }



    public function getMaxSubtitleNumberByVideoId($videoId)
    {
        return $this->subtitle->where('video_id', $videoId)->max('subtitle_number');
    }
}
