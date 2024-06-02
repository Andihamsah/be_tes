<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shop = Auth::user()->shop;
        $role = Auth::user()->roles()->first();
        return ResponseHelper::success(['shop' => $shop, 'role' => $role], 'berhasil', 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $user = Auth::user();

        $store = Shop::create([
            'namaToko' => $user->name,
            'user_id' => $user->id,
        ]);
        $role = Role::where(['guard_name' => 'web', 'name' => 'admin'])->get();

        $user->assignRole($role);
        $user->save();

        return ResponseHelper::success(['store' => $store], 'Toko Berhasil Dibuat', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(shop $shop)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(shop $shop)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, shop $shop)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $shop = $user->shop;

        if (!$shop) {
            return response()->json(['message' => 'Shop not found'], 404);
        }

        $shop->namaToko = $request->name;
        $shop->save();

        return response()->json(['message' => 'Shop name updated successfully', 'shop' => $shop]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(shop $shop)
    {
        //
    }
}
