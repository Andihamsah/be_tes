<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index() {
        $user = Auth::user();
        $products = $user->products()->with('shop')->get();
        return ResponseHelper::success($products, 200);
    }

    public function store(Request $request) {
        $request->validate([
            'namaBarang' => 'required|string|max:255',
            'asal' => 'nullable|string',
            'rating' => 'required|string',
            'harga' => 'required|numeric',
            'jumlahBarang' => 'required|numeric',
        ]);

        $user = Auth::user();
        $store = Shop::find($request->toko_id);

        if ($store->user_id != $user->id) {
            return ResponseHelper::error(['error' => 'Unauthorized action.'], 403);
        }
        $imagePath = $request->file('image')->store('products', 'public');

        $product = new Product([
            'img' => $imagePath,
            'namaBarang' => $request->namaBarang,
            'asal' => $request->asal,
            'rating' => $request->rating,
            'harga' => $request->harga,
            'jumlahBarang' => $request->jumlahBarang,
            'user_id' => $user->id,
            'toko_id' => $store->id,
        ]);

        $product->save();

        return ResponseHelper::success($product, 'success', 201);
    }

    public function show($id) {
        $user = Auth::user();
        $product = $user->products()->with('shop')->findOrFail($id);
        return ResponseHelper::success($product, 200);
    }

    public function update(Request $request, $id) {
        $request->validate([
            'namaBarang' => 'required|string|max:255',
            'asal' => 'nullable|string',
            'rating' => 'required|string',
            'harga' => 'required|numeric',
            'jumlahBarang' => 'required|numeric',
        ]);

        $user = Auth::user();
        $product = $user->products()->findOrFail($id);

        $store = Shop::find($request->toko_id);

        if ($store->user_id != $user->id) {
            return ResponseHelper::error(['error' => 'Unauthorized action.'], 403);
        }

        $product->update([
            'namaBarang' => $request->namaBarang,
            'asal' => $request->asal,
            'rating' => $request->rating,
            'harga' => $request->harga,
            'jumlahBarang' => $request->jumlahBarang,
        ]);

        return ResponseHelper::success($product, 200);
    }

    public function destroy($id) {
        $user = Auth::user();
        $product = $user->products()->findOrFail($id);
        $product->delete();

        return ResponseHelper::success(['message' => 'Product deleted successfully.'], 200);
    }
}
