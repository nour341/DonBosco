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
            'status',
            'total',
            'balance',
            ];


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


}
