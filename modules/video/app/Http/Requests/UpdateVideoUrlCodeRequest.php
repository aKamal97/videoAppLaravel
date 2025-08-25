<?php

namespace Modules\Video\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVideoUrlCodeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [

            'url'=> ['string','url','max:255'],
            'start'=>['integer','min:0'],
            'end'=>['integer','min:1','gt:start'],
            'isembedcode'=>['boolean'],
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
