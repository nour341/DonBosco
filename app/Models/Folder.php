<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'project_id',
        'folder_parent_id',
        'name',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function files()
    {
        return $this->hasMany(File::class);
    }
}



