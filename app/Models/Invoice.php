<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';

    protected $fillable = [
        'status',
        'profits',
        'paid_price',
        'total_price',
        'customer_id'
    ];

    public function products()
    {
        return $this->belongsToMany(InvoiceProduct::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
