<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan membuat request ini.
     * Untuk API publik, kita set ke true.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Dapatkan aturan validasi yang berlaku untuk request ini.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_produk' => 'required|string|max:100',
            'kode_produk' => 'required|string|max:20|unique:products,kode_produk', // Ganti 'kopers' menjadi 'products'
            'harga' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
        ];
    }
}
