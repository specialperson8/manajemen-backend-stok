<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest; // Panggil Form Request yang baru dibuat
use App\Http\Requests\UpdateProductRequest; // Panggil Form Request yang baru dibuat
use App\Models\Product; // Gunakan model Product
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    /**
     * Mengambil daftar produk, mendukung pencarian.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $products = Product::query()
                ->when($request->input('search'), function ($query, $searchTerm) {
                    $query->where('nama_produk', 'like', "%{$searchTerm}%");
                })
                ->orderBy('id', 'asc')
                ->get();

            return $this->sendSuccess('Produk berhasil diambil.', $products);

        } catch (Exception $e) {
            return $this->sendError('Gagal mengambil data.', $e->getMessage());
        }
    }

    /**
     * Menyimpan produk baru.
     * Validasi ditangani oleh StoreProductRequest.
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        try {
            // Data sudah divalidasi oleh Form Request, aman untuk langsung dibuat.
            $product = Product::create($request->validated());
            return $this->sendSuccess('Produk berhasil dibuat.', $product, 201);

        } catch (Exception $e) {
            return $this->sendError('Gagal menyimpan produk.', $e->getMessage());
        }
    }

    /**
     * Menampilkan detail satu produk.
     */
    public function show(Product $product): JsonResponse
    {
        // Menggunakan Route Model Binding, Laravel otomatis mencari produk
        // atau melempar 404 jika tidak ditemukan.
        return $this->sendSuccess('Detail produk ditemukan.', $product);
    }

    /**
     * Memperbarui produk yang ada.
     * Validasi ditangani oleh UpdateProductRequest.
     */
    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        try {
            // Data sudah divalidasi, produk sudah ditemukan via Route Model Binding.
            $product->update($request->validated());
            return $this->sendSuccess('Produk berhasil diperbarui.', $product);

        } catch (Exception $e) {
            return $this->sendError('Gagal memperbarui produk.', $e->getMessage());
        }
    }

    /**
     * Menghapus produk.
     */
    public function destroy(Product $product): JsonResponse
    {
        try {
            $product->delete();
            return $this->sendSuccess('Produk berhasil dihapus.');

        } catch (Exception $e) {
            return $this->sendError('Gagal menghapus produk.', $e->getMessage());
        }
    }

    // =================================================================
    // FUNGSI BANTUAN UNTUK RESPONSE (PRIVATE METHODS)
    // =================================================================

    /**
     * Mengirim response sukses yang terstandarisasi.
     */
    private function sendSuccess(string $message, $data = null, int $statusCode = 200): JsonResponse
    {
        $response = [
            'status' => true,
            'message' => $message,
        ];

        if ($data) {
            $response['data'] = $data;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Mengirim response error yang terstandarisasi.
     */
    private function sendError(string $message, $errors = null, int $statusCode = 500): JsonResponse
    {
        $response = [
            'status' => false,
            'message' => $message,
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }
}
