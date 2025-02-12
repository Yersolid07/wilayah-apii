<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Province extends Model
{
    use HasFactory;

    protected $table = 'provinces_code';
    protected $primaryKey = 'code'; // Primary key adalah 'code'
    public $incrementing = false; // Karena 'code' bukan auto-increment

    protected $fillable = ['code', 'name'];

    public function cities()
    {
        return $this->hasMany(City::class, 'province_code', 'code');
    }
}
