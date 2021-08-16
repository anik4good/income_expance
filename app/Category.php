<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $guarded = [''];

    public function incomes()
    {
        return $this->hasMany(Income::class);
    }

    public function incomes_100()
    {
        return $this->hasMany(Income::class);
    }
}
