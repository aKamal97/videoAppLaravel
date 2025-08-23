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
    public function getSectionById($id)
    {
        return $this->sectionRepository->getSectionById($id);
    }
    public function createSection($videoId,array $data)
    {
        $data['video_id'] = $videoId;
        return $this->sectionRepository->createSection($data);
    }
    public function updateSection($id, array $data)
    {
        return $this->sectionRepository->updateSection($id, $data);
    }
    public function deleteSection($id)
    {
        return $this->sectionRepository->deleteSection($id);
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