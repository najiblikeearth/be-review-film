<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Roles;
use Database\Seeders\RoleSeeder;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $role = Roles::all();

        return response([
            "message" => "Data berhasil ditampilkan",
            "data" => $role
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|min:2'
        ], [
            'required' => 'inputan :attribute harus diisi'
        ]);

        $role = new Roles;

        $role->name = $request->input('name');

        $role->save();

        return response([
            "message" => "Data berhasil ditambahkan"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = Roles::find($id);
        if (!$role) {
            return response([
                "message" => "Data Role tidak ditemukan"
            ], 404);
        }

        return response([
            "message" => "Detail Data",
            "data" => $role
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|min:2',
        ], [
            'required' => 'inputan :attribute harus diisi'
        ]);

        $role = Roles::find($id);
        if (!$role) {
            return response([
                "message" => "Data tidak ditemukan"
            ], 404);
        }

        $role->name = $request->input('name');

        $role->save();

        return response([
            "message" => "Data berhasil Diupdate"
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Roles::find($id);

        if (!$role) {
            return response([
                "message" => "Data Role tidak ditemukan"
            ], 404);
        }

        $role->delete();

        return response([
            "message" => "Data Detail berhasil Dihapus"
        ], 200);
    }
}
