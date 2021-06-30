<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nspace extends Model
{
    use HasFactory;

    protected $table = 'k_namespaces';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $primaryKey = 'uid';


    protected $fillable = [
        'uid',
        'name',
        'report_uid',
        'kubernetes_namespace_uid'
    ];

    public function report()
    {
        return $this->belongsTo(Report::class, 'report_uid', 'uid');
    }
}
