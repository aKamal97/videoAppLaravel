<?php

namespace Modules\Video\App\Repositories\Eloquent;

use Modules\Video\App\Repositories\Contract\VideoRepositoryInterface;
use Modules\Video\App\Models\Video;

class VideoRepository implements VideoRepositoryInterface
{
   private $video ;
    public function __construct(Video $video)
    {
         $this->video = $video;
    }
   public function getAll()
   {
       // Implementation for getting all videos
       return $this->video->all();
   }
    public function getById($id)
    {
         // Implementation for getting a video by ID
         return $this->video->findOrFail($id);
    }
    public function create(array $data)
    {
  
        // Implementation for creating a new video
        return $this->video->create($data);
    }
    public function update($id, array $data)
    {
        // Implementation for updating a video
        $video = $this->video->findOrFail($id);
        if ($video) {
            $video->update($data);
            return $video;
        }
        return null;
    }
    public function delete($id)
    {
        // Implementation for deleting a video
        $video = $this->video->find($id);
        if ($video) {
            $video->delete();
            return true;
        }
        return false;
    }

}