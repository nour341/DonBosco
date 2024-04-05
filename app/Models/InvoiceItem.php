<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'item_id',
        'invoice_id',
        'unit_price',
        'quantity',
        'total_price_quantity',
    ];

    public function items(){
        return $this->belongsTo(Item::class,'item_id','id');

    }
    public function invoices()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }

}
