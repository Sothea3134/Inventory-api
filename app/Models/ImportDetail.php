<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class ImportDetail extends Model
{
    use HasFactory;

    protected $table = 'import_details';
    protected $fillble = ['qty', 'price'];

    protected $hidden = ['product_id'];

    protected $primaryKey = 'id';

    public function import()
    {
        return $this->belongsTo(Import::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
