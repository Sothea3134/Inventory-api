<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    use HasFactory;

    public function importDetails()
    {
        return  $this->hasMany(ImportDetail::class);
    }
}
