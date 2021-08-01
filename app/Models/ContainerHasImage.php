<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContainerHasImage extends Model
{
    use HasFactory;

    protected $table = 'k_container_has_images';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $primaryKey = 'uid';

    protected $fillable = [
        'report_uid',
        'container_uid',
        'image_uid'
    ];

    public function report()
    {
        return $this->belongsTo(Report::class, 'report_uid', 'uid');
    }

    public function container()
    {
        return $this->belongsTo(Container::class, 'container_uid', 'uid');
    }

    public function image()
    {
        return $this->belongsTo(Image::class, 'image_uid', 'uid');
    }
}
