<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class InvoiceProduct extends Model
{
    use HasFactory;

    protected $fillable = ['invoice_id', 'product_id','user_id', 'qty', 'sale_price'];

    function product() {
        return $this->belongsTo(Product::class);
    }
}
