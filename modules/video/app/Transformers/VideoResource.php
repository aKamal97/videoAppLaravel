<?php

namespace Modules\Video\App\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'url' => $this->url,
             'length' => $this->length,
            'time_section_threshold' => $this->time_section_threshold,
            'video_sections_bool' => $this->video_sections_bool,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}