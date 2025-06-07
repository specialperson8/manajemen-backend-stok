<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Ambil ID produk dari route parameter
        $productId = $this->route('product'); // 'product' adalah nama resource di api.php

        return [
            'nama_produk' => 'required|string|max:100',
            // Aturan unik dengan pengecualian untuk ID saat ini
            'kode_produk' => 'required|string|max:20|unique:products,kode_produk,' . $productId,
            'harga' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
        ];
    }
}
