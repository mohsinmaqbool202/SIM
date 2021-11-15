<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    use HasFactory;

    public const STOCK_IN = 'in';
    public const STOCK_OUT = 'out';

    public function product() {
    	return $this->belongsTo(Product::class);
    }

    public function size() {
    	return $this->belongsTo(Size::class);
    }
}
