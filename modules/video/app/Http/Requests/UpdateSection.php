<?php

namespace Modules\Video\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSection extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [

            'start' => ['integer','min:0'],
            'end' => ['integer','min:1','gt:start'],
            'title' => ['string','max:255'],
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
