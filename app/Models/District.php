<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class District extends Model
{
    use HasFactory;

    protected $table = 'districts_ref';
    protected $primaryKey = 'district_id';
    public $incrementing = false;

    protected $fillable = [
        'district_id',
        'regency_id',
        'province_id',
        'name',
        'is_active',
        'created_by',
        'updated_by'
    ];

    public function regency()
    {
        return $this->belongsTo(Regency::class, 'regency_id', 'regency_id');
    }

    public function villages()
    {
        return $this->hasMany(Village::class, 'district_id', 'district_id');
    }
}
