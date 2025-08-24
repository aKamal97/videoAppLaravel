<?php

namespace Modules\Video\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVideoQuizRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
{
   return [
        'quize_number' => 'sometimes|integer',
        'start'        => 'sometimes|integer|min:0',
        'end'          => 'sometimes|integer|gte:start',
        'questionType' => 'sometimes|integer', 
        'question'     => 'sometimes|string|max:191',
        'answer1'      => 'sometimes|string|max:191|nullable',
        'answer2'      => 'sometimes|string|max:191|nullable',
        'answer3'      => 'sometimes|string|max:191|nullable',
        'answer4'      => 'sometimes|string|max:191|nullable',
        'answer5'      => 'sometimes|string|max:191|nullable',
    ];
}

}
