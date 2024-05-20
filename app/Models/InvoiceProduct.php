<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceProduct extends Model
{
    use HasFactory;

    protected $table = 'invoice_products';

    protected $fillable = [
        'price',
        'quantity',
        'invoice_id',
        'product_id'
    ];
}
