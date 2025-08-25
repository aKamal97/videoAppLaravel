<?php

namespace Modules\Video\App\Services\Repositories;

use Modules\Video\App\Repositories\Contract\VideoRepositoryInterface;
use Modules\Video\App\Services\Contract\VideoServiceInterface;

class VideoServiceRepository implements VideoServiceInterface
{
    protected $videoRepository;
    public function __construct(VideoRepositoryInterface $videoRepository)
    {
        $this->videoRepository = $videoRepository;
    }
    public function getAll()
    {
        return $this->videoRepository->getAll();
    }
    public function getById($id)
    {
       
        return $this->videoRepository->getById($id);
    }
    public function create(array $data)
    {
        return $this->videoRepository->create($data);
    }
    public function update($id, array $data)
    {
        return $this->videoRepository->update($id, $data);
    }
    public function delete($id)
    {
        return $this->videoRepository->delete($id);
    }
}
