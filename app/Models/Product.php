<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Daftarkan kolom yang boleh diisi lewat create() atau update()
    protected $fillable = [
        'nama_produk',
        'kode_produk',
        'harga',
        'stok',
        'deskripsi',
    ];
}
