<?php

namespace Modules\Video\App\Repositories\Eloquent;

use Modules\Video\App\Models\VideoSubtitles;
use Modules\Video\App\Repositories\Contract\SubtitleRepositoryInterface;

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

    public function updateSubtitle($id, array $data)
    {
        $subtitle = $this->getSubtitleById($id);
        if ($subtitle) {
            $subtitle->update($data);
            return $subtitle;
        }
        return null;
    }

    public function deleteSubtitle($id)
    {
        $subtitle = $this->getSubtitleById($id);
        if ($subtitle) {
            $subtitle->delete();
            return true;
        }
        return false;
    }

    public function getSubtitlesByVideoId($videoId)
    {
        return $this->subtitle->where('video_id', $videoId)->get();
    }

    public function getMaxSubtitleNumberByVideoId($videoId)
    {
        return $this->subtitle->where('video_id', $videoId)->max('subtitle_number');
    }
}
