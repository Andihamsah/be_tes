<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index() {
        $user = Auth::user();
        $products = Product::->get();
        // $image =  Storage::disk('public')->get('storage/'.$products[2]->img);
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
        $shop = Shop::find($request->shop_id);

        if ($shop->user_id != $user->id) {
            return ResponseHelper::error(['error' => 'Unauthorized action.'], 403);
        }
        // $imagePath = $request->file('img')->store('storage/products', 'public');
        $fileName = explode('.', $request->file('img')->getClientOriginalName())[0];
        $imagePath = Storage::disk('public')->put($fileName, $request->file('img'));

        $product = new Product([
            'img' => $imagePath,
            'namaBarang' => $request->namaBarang,
            'asal' => $request->asal,
            'rating' => $request->rating,
            'harga' => $request->harga,
            'jumlahBarang' => $request->jumlahBarang,
            'user_id' => $user->id,
            'shop_id' => $shop->id,
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
        $product = Product::findOrFail($id);

        $shop = Shop::find($request->shop_id);

        if ($shop->user_id != $user->id) {
            return ResponseHelper::error(['error' => 'Unauthorized action.'], 403);
        }
        if ($request->file('img') !== null){
            Storage::delete($product->img);
            $fileName = explode('.', $request->file('img')->getClientOriginalName())[0];
            $imagePath = Storage::disk('public')->put($fileName, $request->file('img'));
            $product->update([
                'namaBarang' => $request->namaBarang,
                'asal' => $request->asal,
                'rating' => $request->rating,
                'harga' => $request->harga,
                'jumlahBarang' => $request->jumlahBarang,
                'img' => $imagePath,
            ]);
        } else {
            $product->update([
                'namaBarang' => $request->namaBarang,
                'asal' => $request->asal,
                'rating' => $request->rating,
                'harga' => $request->harga,
                'jumlahBarang' => $request->jumlahBarang,
            ]);

        }

        $productUpdate = Product::findOrFail($id);

        return ResponseHelper::success($productUpdate, 200);
    }

    public function destroy($id) {
        $user = Auth::user();
        $product = $user->products()->findOrFail($id);
        $product->delete();

        return ResponseHelper::success(['message' => 'Product deleted successfully.'], 200);
    }
}
