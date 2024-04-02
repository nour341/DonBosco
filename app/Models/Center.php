<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Country;
use App\Models\Project;
class Center extends Model
{
    protected $table = 'centers';
    protected $fillable = ['name','address','image_path','country_id'];

    public function country()
       {
           return $this->belongsTo(Country::class); // تأكد من استخدام الاسم الصحيح لموديل Country
       }
       public function projects()
       {
           return $this->hasMany(Project::class);
       }
    use HasFactory;
}
