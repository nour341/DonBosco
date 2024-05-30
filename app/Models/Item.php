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



    public function invoices()
    {
        return $this->belongsToMany(Invoice::class, InvoiceItem::class,
            'item_id','invoice_id',  'id', 'id');
    }


}
