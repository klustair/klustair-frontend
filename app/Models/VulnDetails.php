<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VulnDetails extends Model
{
    use HasFactory;

    protected $table = 'k_vuln_details';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $primaryKey = 'uid';

    protected $fillable = [
        'title',
        'descr',
        'fixed_version',
        'severity_source',
        'severity',
        'last_modified_date',
        'published_date',
        'links',
        'cvss',
        'cwe_ids'
    ];
}
