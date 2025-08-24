<?php

namespace Modules\Video\App\Repositories\Eloquent;
use Modules\Video\App\Models\VideoUrlCode;
use Modules\Video\App\Repositories\Contract\VideoUrlCodeInterface;

class VideoUrlCodeRepository implements VideoUrlCodeInterface
{
   private $videoUrlCode ;
    public function __construct(VideoUrlCode $videoUrlCode)
    {
         $this->videoUrlCode = $videoUrlCode;
    }
   public function getAllUrls()
   {
       // Implementation for getting all video URL codes
       return $this->videoUrlCode->all();
   }
    public function getUrlCodeByVideoId($videoId,$urlCodeId)
    {
         return $this->videoUrlCode->where('video_id', $videoId)->where('id', $urlCodeId)->first();
    }
    public function createUrlCode(array $data)
    {

        // Implementation for creating a new video URL code
        return $this->videoUrlCode->create($data);
    }
    public function updateUrlCode($videoId,$urlCodeId, array $data)
    {

        // Implementation for updating a video URL code
        $urlCode = $this->getUrlCodeByVideoId($videoId,$urlCodeId);
        if ($urlCode) {
            $urlCode->update($data);
            return $urlCode;
        }
        return null;
    }
    public function deleteUrlCode($videoId,$urlCodeId)
    {
        // Implementation for deleting a video URL code
        $urlCode = $this->getUrlCodeByVideoId($videoId,$urlCodeId);
        if ($urlCode) {
            $urlCode->delete();
            return true;
        }
        return false;
    }
    public function getMaxUrlCodeNumberByVideoId($videoId)
    {
        // Implementation for getting the maximum URL code number by video ID
        return $this->videoUrlCode->where('video_id', $videoId)->max('url_number');
    }
    public function getUrlCodeNumbersByVideoId($videoId)
    {
        // Implementation for getting all URL code numbers by video ID
        return $this->videoUrlCode->where('video_id', $videoId)->pluck('url_number');
    }
    public function getUrlCodesByVideoId($videoId)
    {
        // Implementation for getting all URL codes by video ID
        return $this->videoUrlCode->where('video_id', $videoId)->get();
    }
}