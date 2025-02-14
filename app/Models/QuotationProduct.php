<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationProduct extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','quotation_id', 'product_id', 'price', 'quantity', 'subtotal', 'total'];

}
