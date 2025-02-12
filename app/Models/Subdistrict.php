<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subdistrict extends Model
{
    use HasFactory;

    protected $table = 'subdistricts_code';
    protected $primaryKey = 'code'; // Primary key adalah 'code'
    public $incrementing = false;

    protected $fillable = ['district_code', 'code', 'name'];

    public function district()
    {
        return $this->belongsTo(District::class, 'district_code', 'code');
    }
}
