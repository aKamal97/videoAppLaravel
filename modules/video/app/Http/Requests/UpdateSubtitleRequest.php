<?php

namespace Modules\Video\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSubtitleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'start'             => 'sometimes|integer|min:0',
            'end'               => 'sometimes|integer|gt:start',
            'text'              => 'sometimes|string|max:191',
            'video_id'          => 'required|exists:videos,id',
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
