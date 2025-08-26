<?php

namespace Modules\Video\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Video\Database\Factories\VideoUrlCodeFactory;

class VideoUrlCode extends Model
{
    use HasFactory;
    protected $table = 'video_url_codes';
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'id',
        'video_id',
        'url',
        'start',
        'end',
        'url_number',
        'isembedcode'
    ];

    public function video()
    {
        return $this->belongsTo(Video::class);
    }
    // protected static function newFactory(): VideoUrlCodeFactory
    // {
    //     // return VideoUrlCodeFactory::new();
    // }
}
