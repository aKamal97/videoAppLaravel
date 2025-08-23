<?php

namespace Modules\Video\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateVideoRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => ['required','string','max:255'],
            'url'=> ['required','string','url','max:255'],
            'length' => ['required','integer','min:1'],
            'time_section_threshold' => ['required','integer','min:1'],
            'video_sections_bool' => ['required','boolean'],


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