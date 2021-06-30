<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VulnTrivy extends Model
{
    use HasFactory;

    protected $table = 'k_vuln_trivy';
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
        'title',
        'descr',
        'installed_version',
        'fixed_version',
        'severity_source',
        'severity',
        'last_modified_date',
        'published_date',
        'links',
        'cvss',
        'cwe_ids'
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
}
