<?php

namespace Modules\Video\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSectionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'sections' => ['required','array','min:1'],
            'sections.*.start' => ['required','integer','min:0'],
            'sections.*.end' => ['required','integer','min:1','gt:sections.*.start'],
            'sections.*.title' => ['required','string','max:255'],
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
