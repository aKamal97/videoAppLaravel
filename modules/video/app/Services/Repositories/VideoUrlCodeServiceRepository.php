<?php
namespace Modules\Video\App\Services\Repositories;
use Modules\Video\App\Repositories\Contract\VideoUrlCodeInterface;
use Modules\Video\App\Services\Contract\VideoUrlCodeServiceInterface;
class VideoUrlCodeServiceRepository implements VideoUrlCodeServiceInterface
{
    protected $videoUrlCodeRepository;
    public function __construct(VideoUrlCodeInterface $videoUrlCodeRepository)
    {
        $this->videoUrlCodeRepository = $videoUrlCodeRepository;
    }
    public function getAllUrls()
    {
        return $this->videoUrlCodeRepository->getAllUrls();
    }
    public function getUrlCodesByVideoId($videoId)
    {
        return $this->videoUrlCodeRepository->getUrlCodesByVideoId($videoId);
    }
    public function createUrlCode($videoId,array $data)
    {
        $data['video_id'] = $videoId;
        return $this->videoUrlCodeRepository->createUrlCode($data);
    }
    public function getUrlCodeByVideoId($videoId,$urlCodeId)
    {
        return $this->videoUrlCodeRepository->getUrlCodeByVideoId($videoId,$urlCodeId);
    }
    public function updateUrlCode($videoId,$urlCodeId, array $data)
    {
        return $this->videoUrlCodeRepository->updateUrlCode($videoId,$urlCodeId, $data);
    }
    public function deleteUrlCode($videoId,$urlCodeId)
    {
        return $this->videoUrlCodeRepository->deleteUrlCode($videoId,$urlCodeId);
    }
    public function getMaxUrlCodeNumberByVideoId($videoId)
    {
        return $this->videoUrlCodeRepository->getMaxUrlCodeNumberByVideoId($videoId);
    }
    public function getUrlCodeNumbersByVideoId($videoId)
    {
        return $this->videoUrlCodeRepository->getUrlCodeNumbersByVideoId($videoId);
    }
}