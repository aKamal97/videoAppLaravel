<?php

namespace Modules\Video\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSubtitleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'subtitles'                     => 'required|array',
            'subtitles.*.id'                => 'sometimes|exists:video_subtitles,id',
            'subtitles.*.video_id'          => 'sometimes|exists:videos,id',
            'subtitles.*.start'             => 'sometimes|integer|min:0',
            'subtitles.*.end'               => 'sometimes|integer|gt:subtitles.*.start',
            'subtitles.*.text'              => 'sometimes|string|max:191',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
