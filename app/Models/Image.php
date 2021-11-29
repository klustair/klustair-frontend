<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $table = 'k_images';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $primaryKey = 'uid';

    protected $fillable = [
        'uid',
        'image_b64', 
        'report_uid', 
        'analyzed_at', 
        'created_at', 
        'fulltag', 
        'image_digest', 
        'arch', 
        'distro', 
        'distro_version', 
        'image_size', 
        'layer_count', 
        'registry', 
        'repo',
        'dockerfile',
        'config',
        'history'
    ];

    public function report()
    {
        return $this->belongsTo(Report::class, 'report_uid', 'uid');
    }
}
