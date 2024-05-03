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
    protected $fillable =
        [
            'name',
            'local_coordinator_id',
            'financial_management_id',
            'supplier_id',
            'short_description',
            'start_date',
            'end_date',
            'center_id',
            'status'];


    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    public function center()
       {
           return $this->belongsTo(Center::class);
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
    public function  getStatus(){

        return $this ->status == 1 ? "Publication" : "Unpublished";

    }


}
