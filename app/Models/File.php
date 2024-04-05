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
        'name',
        'type',
    ];

    public function folders()
    {
        return $this->belongsTo(Folder::class);
    }
}
