<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Container extends Model
{
    use HasFactory;

    protected $table = 'k_containers';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $primaryKey = 'uid';

    protected $fillable = [
        'uid',
        'report_uid',
        'namespace_uid',
        'pod_uid',
        'name',
        'image',
        'image_pull_policy',
        'security_context',
        'init_container',
        'ready',
        'started',
        'restart_count',
        'started_at'
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
