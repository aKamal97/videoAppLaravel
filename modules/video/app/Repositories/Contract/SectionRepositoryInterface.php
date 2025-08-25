<?php

namespace Modules\Video\App\Repositories\Contract;

interface SectionRepositoryInterface {
    // Define the methods that the SectionRepository should implement
    public function getAllSections();
    public function createSection(array $data);
    public function getSectionByVideoId($videoId,$sectionId);
    public function updateSection($videoId,$sectionId, array $data);
    public function deleteSection($videoId,$sectionId);
    public function getMaxSectionNumberByVideoId($videoId);
    public function getSectionNumbersByVideoId($videoId);
    public function getSectionsByVideoId($videoId);


}