<?php

namespace Modules\Video\App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class QuizResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id'            => $this->id,
            'quize_number'  => $this->quize_number,
            'video_id'      => $this->video_id,
            'start'         => $this->start,
            'end'           => $this->end,
            'question_type' => $this->questionType,
            'question'      => $this->question,
            'answers'       => [
                'answer1' => $this->answer1,
                'answer2' => $this->answer2,
                'answer3' => $this->answer3,
                'answer4' => $this->answer4,
                'answer5' => $this->answer5,
            ],
            'created_at'    => $this->created_at ? $this->created_at->toDateTimeString() : null,
            'updated_at'    => $this->updated_at ? $this->updated_at->toDateTimeString() : null,
        ];
    }
}
