<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';
    protected $fillable = ['project_id','description','start_date','end_date','status_id','user_id'];

    public function project()
       {
           return $this->belongsTo(Project::class);
       }

    public function status()
    {
           return $this->belongsTo(StatusTask::class,'status_id');
    }


    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'task_id', 'id');
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'task_id', 'id');
    }

    public function comment()
    {
        return $this->hasMany(Comment::class, 'task_id', 'id');
    }



}
