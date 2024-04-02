<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project;

class Task extends Model
{
    protected $table = 'tasks';
    protected $fillable = ['project_id','description','start_date','end_date','status'];

    public function project()
       {
           return $this->belongsTo(Project::class);
       }

    use HasFactory;
}
