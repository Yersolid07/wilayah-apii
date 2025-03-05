<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    protected $table = 'villages_ref';
    protected $primaryKey = 'village_id';
    public $incrementing = false;
    
    protected $fillable = [
        'village_id',
        'province_id',
        'regency_id',
        'district_id',
        'name',
        'is_active',
        'created_by',
        'updated_by'
    ];

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id', 'district_id');
    }

    public function regency()
    {
        return $this->belongsTo(Regency::class, 'regency_id', 'regency_id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id', 'province_id');
    }
} 