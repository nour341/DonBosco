<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'task_id',
        'number',
        'from',
        'to',
        'date',
        'total_price',
        'status',
        'image_id',
    ];


    public function task()
    {
        return $this->belongsTo(Task::class ,'task_id','id');
    }


    public function items()
    {
        return $this->belongsToMany(ItemBudget::class, InvoiceItem::class,
            'invoice_id','itemBudget_id',  'id', 'id');
    }

    public function invoice_items()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id', 'id');
    }




    public function image()
    {
        return $this->belongsTo(File::class,'image_id');
    }



    protected $hidden = [
        'created_at',
        'updated_at',
    ];


}
