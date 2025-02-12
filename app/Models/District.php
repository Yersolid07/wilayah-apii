<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class District extends Model
{
    use HasFactory;

    protected $table = 'districts_code';
    protected $primaryKey = 'code'; // Primary key adalah 'code'
    public $incrementing = false;

    protected $fillable = ['city_code', 'code', 'name'];

    public function city()
    {
        return $this->belongsTo(City::class, 'city_code', 'code');
    }

    public function subdistricts()
    {
        return $this->hasMany(Subdistrict::class, 'district_code', 'code');
    }
}
