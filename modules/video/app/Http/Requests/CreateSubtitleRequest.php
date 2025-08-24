<?php

namespace Modules\Video\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSubtitleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'subtitles'                     => 'required|array',
            'subtitles.*.start'             => 'required|integer|min:0',
            'subtitles.*.end'               => 'required|integer|gt:subtitles.*.start',
            'subtitles.*.text'              => 'required|string|max:191',
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
