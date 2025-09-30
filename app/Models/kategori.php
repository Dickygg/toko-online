<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/** @use HasFactory<\Database\Factories\KategoriFactory> */

class kategori extends Model
{
    public $timestamps = true;
    protected $table = 'kategori';
    protected $guarded = ['id'];
}
