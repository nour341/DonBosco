<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'folder_id',
        'description',
        'type',
        'name',
    ];

    public function folder()
    {
        return $this->belongsTo(Folder::class,'folder_id');
    }
}
