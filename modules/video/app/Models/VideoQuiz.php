<?php
namespace Modules\Video\App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoQuiz extends Model
{
    protected $table = 'video_quizzes';
    protected $fillable = [
        'quize_number',
        'video_id',
        'start',
        'end',
        'questionType',
        'question',
        'answer1',
        'answer2',
        'answer3',
        'answer4',
        'answer5',
    ];

    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
