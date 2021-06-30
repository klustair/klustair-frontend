<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    use HasFactory;

    protected $table = 'k_audits';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $primaryKey = 'uid';

    protected $fillable = [
        'uid',
        'namespace_uid',
        'report_uid',

        'audit_type',
        'audit_name',
        'msg',
        'severity_level',
        'audit_time',
        'resource_name',
        'capability',
        'container',
        'missing_annotation',
        'resource_namespace',
        'resource_api_version'
    ];

    public function report()
    {
        return $this->belongsTo(Report::class, 'report_uid', 'uid');
    }

    public function namespace()
    {
        return $this->belongsTo(Nspace::class, 'namespace_uid', 'uid');
    }
}
