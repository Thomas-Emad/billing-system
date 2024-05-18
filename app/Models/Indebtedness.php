<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indebtedness extends Model
{
    use HasFactory;

    protected $table = 'indebtednesses';

    protected $fillable = [
        'debtor',
        'invoice_id',
        'customer_id'
    ];

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function invoice() {
        return $this->belongsTo(Invoice::class);
    }
}
