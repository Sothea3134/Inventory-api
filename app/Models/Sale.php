<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;
    protected $table = 'sales';

    protected $primaryKey = 'id';

    public function saleDetails()
    {
        return  $this->hasMany(SaleDetail::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
