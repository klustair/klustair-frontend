<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetTrivy extends Model
{
    use HasFactory;

    protected $table = 'k_target_trivy';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $primaryKey = 'uid';

    protected $fillable = [
        'uid',
        'report_uid',
        'image_uid',
        'target',
        'target_type',
        'is_os'
    ];

    public function report()
    {
        return $this->belongsTo(Report::class, 'report_uid', 'uid');
    }

    public function image()
    {
        return $this->belongsTo(Image::class, 'image_uid', 'uid');
    }
}
