<?php

namespace Modules\Video\App\Repositories\Eloquent;

use Modules\Video\App\Repositories\Contract\SectionRepositoryInterface;
use Modules\Video\App\Models\VideoSection;

class SectionRepository implements SectionRepositoryInterface
{
    // Implement the methods defined in the SectionRepositoryInterface
    private $section;
    public function __construct(VideoSection $section)
    {
        $this->section = $section;
    }

    public function getAllSections()
    {
        return $this->section->all();
    }
    public function getSectionById($id)
    {
        return $this->section->findOrFail($id);
    }
    public function createSection(array $data)
    {
        return $this->section->create($data);
    }
    public function updateSection($id, array $data)
    {
        $section = $this->section->getSectionById($id);
        if ($section) {
            $section->update($data);
            return $section;
        }
        return null;
    }
    public function deleteSection($id)
    {
        $section = $this->section->getSectionById($id);
        if ($section) {
            $section->delete();
            return true;
        }
        return false;
    }
    //get section by video id
    public function getSectionsByVideoId($videoId)
    {
        return $this->section->where('video_id', $videoId)->get();
    }
    // get section number by video id
    public function getSectionNumbersByVideoId($videoId)
    {
        return $this->section->where('video_id', $videoId)->pluck('section_number');
    }
    // get max section number by video id
    public function getMaxSectionNumberByVideoId($videoId)
    {
        return $this->section->where('video_id', $videoId)->max('section_number');
    }
}