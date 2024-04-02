<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Center;
class Country extends Model
{
    protected $table = 'countries';
    protected $fillable = ['name'];

    public function centers()
       {
           return $this->hasMany(Center::class);
       }

    use HasFactory;
}
