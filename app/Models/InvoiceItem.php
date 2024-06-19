<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'unite',
        'itemBudget_id',
        'invoice_id',
        'unit_price',
        'quantity',
        'total_price_quantity',
    ];

    public function invoices()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }


    public function itemBudget()
    {
        return $this->belongsTo(ItemBudget::class, 'itemBudget_id');
    }


    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
