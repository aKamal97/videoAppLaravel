<?php

namespace Modules\Video\App\Models;
use Modules\Video\App\Models\Video;
use Illuminate\Database\Eloquent\Model;

class VideoSubtitles extends Model
{

    protected $table = 'video_subtitles';

    protected $fillable = [
        'subtitle_number',
        'video_id',
        'start',
        'end',
        'text',
    ];

    /**
     * Define relationship with Video model
     */
    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
