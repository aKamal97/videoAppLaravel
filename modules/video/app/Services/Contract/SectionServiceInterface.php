<?php
namespace Modules\Video\App\Services\Contract;
interface SectionServiceInterface {
    public function getAllSections();
    public function getSectionById($id);
    public function createSection($videoId, array $data);
    public function updateSection($id, array $data);
    public function deleteSection($id);
    public function getMaxSectionNumberByVideoId($videoId);
    public function getSectionNumbersByVideoId($videoId);
    public function getSectionsByVideoId($videoId);

}
