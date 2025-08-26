<?php

namespace Modules\Video\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Video\App\Models\Video;

// use Modules\Video\Database\Factories\VideoUrlPotatoesFactory;

class VideoUrlPotatoes extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    public function video()
    {
        return $this->belongsTo(Video::class);
    }
    // protected static function newFactory(): VideoUrlPotatoesFactory
    // {
    //     // return VideoUrlPotatoesFactory::new();
    // }
}
