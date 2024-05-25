<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'task_id',
        'path',
    ];


    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}
