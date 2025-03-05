<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Regency extends Model
{
    protected $table = 'regencies_ref';
    protected $primaryKey = 'regency_id';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'regency_id',
        'province_id',
        'name',
        'is_active',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id', 'province_id');
    }

    public function districts()
    {
        return $this->hasMany(District::class, 'regency_id', 'regency_id');
    }
} 