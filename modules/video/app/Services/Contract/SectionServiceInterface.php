<?php
namespace Modules\Video\App\Services\Contract;
interface SectionServiceInterface {
     public function getAllSections();
    public function createSection($videoId,array $data);
    public function getSectionByVideoId($videoId,$sectionId);
    public function updateSection($videoId,$sectionId, array $data);
    public function deleteSection($videoId,$sectionId);
    public function getMaxSectionNumberByVideoId($videoId);
    public function getSectionNumbersByVideoId($videoId);
    public function getSectionsByVideoId($videoId);


}