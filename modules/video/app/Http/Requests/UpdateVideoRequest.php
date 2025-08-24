<?php

namespace Modules\Video\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVideoRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
             'title' => ['string','max:255'],
            'url'=> ['string','url','max:255'],
            'length' => ['integer','min:1'],
            'time_section_threshold' => ['integer','min:1'],
            'video_sections_bool' => ['boolean'],

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
