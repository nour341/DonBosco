<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Center;
use App\Models\User;
use App\Models\Task;
class Project extends Model
{
    protected $table = 'projects';
    protected $fillable = ['name','description','start_date','end_date','center_id'];
    public function center()
       {
           return $this->belongsTo(Center::class); // تأكد من استخدام الاسم الصحيح لموديل Country
       }
       public function users()
       {
           return $this->belongsToMany(User::class);
       }
       public function tasks()
       {
           return $this->hasMany(Task::class);
       }

    use HasFactory;
}
