<?php

namespace App\Models;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Center;
use App\Models\User;
class Project extends Model
{
    use HasFactory;
    protected $table = 'projects';
    protected $fillable = [
        'name',
        'Total',
        'description',
        'start_date',
        'end_date',
        'LocalName',
        'FinancialName',
        'FinName',
        'center_id'];
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
       public function folders()
        {
            return $this->hasMany(Folder::class);
        }


        public function budget()
        {
            return $this->hasOne(Budget::class);
        }



}
