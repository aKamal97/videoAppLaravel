<?php

namespace Modules\Video\App\Repositories\Contract;

interface SectionRepositoryInterface {
    // Define the methods that the SectionRepository should implement
    public function getAllSections();
    public function getSectionById($id);
    public function createSection(array $data);
    public function updateSection($id, array $data);
    public function deleteSection($id);
}
