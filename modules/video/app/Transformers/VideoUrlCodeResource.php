<?php

namespace Modules\Video\App\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoUrlCodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'video_id' => $this->video_id,
            'url_number' => $this->url_number,
            'start' => $this->start,
            'end' => $this->end,
            'url' => $this->url,
            'isembedcode' => $this->isembedcode,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
