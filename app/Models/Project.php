<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Center;
use App\Models\User;
use App\Models\Task;
class Project extends Model
{
    use HasFactory;
    protected $table = 'projects';
    protected $fillable =
        [   'id',
            'name',
            'local_coordinator_id',
            'financial_management_id',
            'supplier_id',
            'short_description',
            'start_date',
            'end_date',
            'center_id',
            'status',
            'total',
            'balance',
            ];




    public function users()
    {
        return $this->belongsToMany(User::class, ProjectUser::class,
            'project_id', 'user_id', 'id', 'id');
    }
    public function tasks()
    {
        return $this->hasMany(Task::class,'project_id');
    }
    public function center()
       {
           return $this->belongsTo(Center::class);
       }



       public function folders()
        {
            return $this->hasMany(Folder::class,'project_id');
        }


        public function itemBudgets()
        {
            return $this->hasMany(ItemBudget::class,'project_id');
        }

        public function items()
        {
            return $this->belongsToMany(Item::class, ItemBudget::class,
                'project_id', 'item_id', 'id', 'id');
        }


    public function  getStatus(){

        return $this ->status == 1 ? "Publication" : "Unpublished";

    }

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
