<?php

namespace Modules\Video\App\Repositories\Contract;
interface VideoUrlCodeInterface {
   public function getAllUrls();
    public function createUrlCode(array $data);
    public function getUrlCodeByVideoId($videoId,$urlCodeId);
    public function updateUrlCode($videoId,$urlCodeId, array $data);
    public function deleteUrlCode($videoId,$urlCodeId);
    public function getMaxUrlCodeNumberByVideoId($videoId);
    public function getUrlCodeNumbersByVideoId($videoId);
    public function getUrlCodesByVideoId($videoId);
}
