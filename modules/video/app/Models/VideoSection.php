<?php

namespace Modules\Video\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Video\Database\Factories\VideoSectionFactory;

class VideoSection extends Model
{
    use HasFactory;
    protected $table = 'video_sections';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
         'id',
        'video_id',
        'start',
        'end',
        'title',
        'section_number',
    ];

    // protected static function newFactory(): VideoSectionFactory
    // {
    //     // return VideoSectionFactory::new();
    // }
}