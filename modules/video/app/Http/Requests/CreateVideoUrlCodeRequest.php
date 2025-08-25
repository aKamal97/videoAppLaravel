<?php

namespace Modules\Video\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateVideoUrlCodeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'url_codes'=>['required','array','min:1'],
            'url_codes.*.url'=> ['required','string','url','max:255'],
            'url_codes.*.start'=>['integer','min:0'],
            'url_codes.*.end'=>['integer','min:1','gt:url_codes.*.start'],
            'url_codes.*.isembedcode'=>['required','boolean'],
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
