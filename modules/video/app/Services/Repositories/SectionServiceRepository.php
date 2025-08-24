<?php
namespace Modules\Video\App\Services\Repositories;

use Modules\Video\App\Repositories\Contract\SectionRepositoryInterface;
use Modules\Video\App\Services\Contract\SectionServiceInterface;

class SectionServiceRepository implements SectionServiceInterface
{
    protected $sectionRepository;
    public function __construct(SectionRepositoryInterface $sectionRepository)
    {
        $this->sectionRepository = $sectionRepository;
    }
    public function getAllSections()
    {
        return $this->sectionRepository->getAllSections();
    }
    public function getSectionByVideoId($videoId,$sectionId)
    {
        return $this->sectionRepository->getSectionByVideoId($videoId,$sectionId);
    }
    public function createSection($videoId,array $data)
    {
        $data['video_id'] = $videoId;
        return $this->sectionRepository->createSection($data);
    }
    public function updateSection($videoId,$sectionId, array $data)
    {
        return $this->sectionRepository->updateSection($videoId,$sectionId, $data);
    }
    public function deleteSection($videoId,$sectionId)
    {
        return $this->sectionRepository->deleteSection($videoId,$sectionId);
    }
    public function getMaxSectionNumberByVideoId($videoId)
    {
        return $this->sectionRepository->getMaxSectionNumberByVideoId($videoId);
    }
    public function getSectionNumbersByVideoId($videoId)
    {
        return $this->sectionRepository->getSectionNumbersByVideoId($videoId);
    }
    public function getSectionsByVideoId($videoId)
    {
        return $this->sectionRepository->getSectionsByVideoId($videoId);
    }
}
