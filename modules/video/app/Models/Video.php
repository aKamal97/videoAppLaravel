<?php

namespace Modules\Video\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Video\Database\Factories\VideoFactory;

class Video extends Model
{
    use HasFactory;
    protected $table = 'videos';
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'id',
        'videoLength',
        'videoTitle',
        'videoUrl',
        'time_section_threshold',
        'video_sections_bool',
    ];

    // protected static function newFactory(): VideoFactory
    // {
    //     // return VideoFactory::new();
    // }
}
