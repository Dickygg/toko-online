<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    public $timestamps = true;
    protected $table = "produk";
    protected $guarded = ['id'];

    // Pada script produk di Laravel, belongsTo adalah metode Eloquent yang digunakan untuk
    // mendefinisikan relasi satu-ke-banyak terbalik (inverse one-to-many) antara model. Ini
    // digunakan ketika model saat ini berhubungan dengan satu entitas dari model lain. Dalam hal
    // ini, model Produk memiliki relasi dengan model Kategori dan User, dimana setiap produk
    // hanya memiliki satu kategori dan satu user yang membuatnya.

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function fotoProduk()
    {
        return $this->hasMany(fotoProduk::class);
    }
}
