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
    public function getSectionByVideoId($videoId, $sectionId)
    {
        return $this->section->where('video_id', $videoId)->where('id', $sectionId)->firstOrFail();
    }

    public function createSection(array $data)
    {
        return $this->section->create($data);
    }
    public function updateSection($videoId, $sectionId, array $data)
    {
        $section = $this->getSectionByVideoId($videoId, $sectionId);
        $section->update($data);
        return $section;
    }
    public function deleteSection($videoId, $sectionId)
    {
        $section = $this->getSectionByVideoId($videoId, $sectionId);
        $section->delete();
        return true;
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
