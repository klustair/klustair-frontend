<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pod extends Model
{
    use HasFactory;

    protected $table = 'k_pods';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $primaryKey = 'uid';

    protected $fillable = [
        'uid',
        'report_uid',
        'namespace_uid',
        'podname',
        'kubernetes_pod_uid',
        'creation_timestamp',
        'age'
    ];

    public function report()
    {
        return $this->belongsTo(Report::class, 'report_uid', 'uid');
    }

    public function namespace()
    {
        return $this->belongsTo(Nspace::class, 'namespace_uid', 'uid');
    }

    protected $casts = [
        'init_container' => 'boolean',
    ];
}
