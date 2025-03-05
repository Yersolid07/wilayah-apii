<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Province extends Model
{
    use HasFactory;

    protected $table = 'provinces_ref';
    protected $primaryKey = 'province_id';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'province_id',
        'name',
        'is_active',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

    public function regencies()
    {
        return $this->hasMany(Regency::class, 'province_id', 'province_id');
    }
}
