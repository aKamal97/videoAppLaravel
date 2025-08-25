<?php

namespace Modules\Video\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateVideoQuizRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'quizzes' => 'required|array',
            'quizzes.*.start' => 'required|integer|min:0',
            'quizzes.*.end' => 'required|integer|gte:quizzes.*.start',
            'quizzes.*.questionType' => 'required|integer',
            'quizzes.*.question' => 'required|string|max:191',
            'quizzes.*.answer1' => 'nullable|string|max:191',
            'quizzes.*.answer2' => 'nullable|string|max:191',
            'quizzes.*.answer3' => 'nullable|string|max:191',
            'quizzes.*.answer4' => 'nullable|string|max:191',
            'quizzes.*.answer5' => 'nullable|string|max:191',
        ];

    }
}
