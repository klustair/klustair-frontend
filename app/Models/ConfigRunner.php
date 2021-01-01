<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfigRunner extends Model
{
    use HasFactory;

    protected $table = 'k_config_runner';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     * 
     */
    protected $fillable = [
        'uid',
        'runner_label',
        'kubeaudit',
        'verbosity',
        'namespacesblacklist',
        'namespaces',
        'anchore',
        'trivy',
        'trivycredentialspath',
        'limit_date',
        'limit_nr'
    ];


    protected $primaryKey = 'uid';
}
