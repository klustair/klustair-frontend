<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportsSummaries extends Model
{
    use HasFactory;

    protected $table = 'k_reports_summaries';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $primaryKey = 'uid';

    protected $fillable = [
        'uid',
        'report_uid',
        'namespaces_checked',
        'namespaces_total',
        'vuln_total',
        'vuln_critical',
        'vuln_high',
        'vuln_medium',
        'vuln_low',
        'vuln_unknown',
        'vuln_fixed',
        'pods',
        'images'
    ];

    public function report()
    {
        return $this->belongsTo(Report::class, 'report_uid', 'uid');
    }
}
