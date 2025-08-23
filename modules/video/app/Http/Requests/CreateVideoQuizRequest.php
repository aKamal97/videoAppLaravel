<?php

namespace Modules\Video\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVideoQuizRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'quize_number' => 'required|integer',
            'video_id' => 'required|exists:videos,id',
            'start' => 'required|integer|min:0',
            'end' => 'required|integer|gte:start',
            'questionType' => 'required|integer',
            'question' => 'required|string|max:191',
            'answer1' => 'nullable|string|max:191',
            'answer2' => 'nullable|string|max:191',
            'answer3' => 'nullable|string|max:191',
            'answer4' => 'nullable|string|max:191',
            'answer5' => 'nullable|string|max:191',
        ];
    }
}
