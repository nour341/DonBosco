<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemBudget extends Model
{
    use HasFactory;


    protected $fillable = [
        'budget_id',
        'item_id',
    ];
}

