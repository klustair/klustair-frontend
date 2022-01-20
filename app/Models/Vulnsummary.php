<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vulnsummary extends Model
{
    use HasFactory;

    protected $table = 'k_vulnsummary';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $primaryKey = 'uid';

    protected $fillable = [
        'uid',
        'report_uid',
        'image_uid',
        'fixed',
        'total',
        'severity',
        'acknowledged',
    ];

    public function report()
    {
        return $this->belongsTo(Report::class, 'report_uid', 'uid');
    }

    public function image()
    {
        return $this->belongsTo(Nspace::class, 'image_uid', 'uid');
    }
}
