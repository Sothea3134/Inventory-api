<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $primaryKey = 'id';

    use HasFactory;

    public function importDetails()
    {
        return $this->hasMany(ImportDetail::class);
    }
    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }
}
