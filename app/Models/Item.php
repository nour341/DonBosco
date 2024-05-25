<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function budgets()
    {
        return $this->belongsToMany(Budget::class, ItemBudget::class,
            'item_id','budget_id',  'id', 'id');
    }

    public function invoices()
    {
        return $this->belongsToMany(Invoice::class, InvoiceItem::class,
            'item_id','invoice_id',  'id', 'id');
    }


}
