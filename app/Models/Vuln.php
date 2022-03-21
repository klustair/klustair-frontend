<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vuln extends Model
{
    use HasFactory;

    protected $table = 'k_vuln';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $primaryKey = 'uid';

    protected $fillable = [
        'uid',
        'image_uid', 
        'report_uid', 
        'target_uid',
        'vulnerability_id',
        'pkg_name',
        'installed_version',
    ];

    public function report()
    {
        return $this->belongsTo(Report::class, 'report_uid', 'uid');
    }

    public function image()
    {
        return $this->belongsTo(Image::class, 'image_uid', 'uid');
    }

    public function target()
    {
        return $this->belongsTo(TargetTrivy::class, 'target_uid', 'uid');
    }

    public function details()
    {
        return $this->belongsTo(TargetTrivy::class, ['vulnerability_id', 'pkg_name', 'installed_version'], ['vulnerability_id', 'pkg_name', 'installed_version']);
    }
}
