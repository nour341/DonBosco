<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemBudget extends Model
{
    use HasFactory;


    protected $fillable = [
        'project_id',
        'id',
        'item_id',
        'unite',
        'unit_price',
        'quantity',
        'total_price',
        'balance'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class,'item_id');
    }


    public function project()
    {
        return $this->belongsTo(Project::class,'project_id');
    }



}

