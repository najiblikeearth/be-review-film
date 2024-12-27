<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Casts;
use PhpParser\Node\Expr\Cast;

class CastController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:api', 'admin'])->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $casts = Casts::all();

        return response([
            "message" => "Berhasil Tampil semua cast",
            "data" => $casts
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required',
            'age' => 'required|integer',
            'bio' => 'required',
        ], [
            'required' => 'inputan :attribute harus diisi tidak boleh kosong.',
            'integer' => 'inputan :attribute harus diisi angka.'
        ]);

        $cast = new Casts;

        $cast->name = $request->input('name');
        $cast->age = $request->input('age');
        $cast->bio = $request->input('bio');

        $cast->save();

        return response([
            "message" => "Berhasil tambah cast"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cast = Casts::find($id);
        if (!$cast) {
            return response([
                "message" => "Data Cast tidak dapat ditemukan"
            ], 404);
        }

        return response([
            "message" => "Berhasil Detail data dengan id $id",
            "data" => $cast
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|min:2',
            'age' => 'required',
            'bio' => 'required',
        ], [
            'required' => 'inputan :attribute harus diisi tidak boleh kosong.',
            'min' => 'inputan :attribute harus :min karakter',
        ]);

        $cast = Casts::find($id);

        $cast->name = $request->input('name');
        $cast->age = $request->input('age');
        $cast->bio = $request->input('bio');

        $cast->save();

        if (!$cast) {
            return response([
                "message" => "Data Cast tidak dapat ditemukan"
            ], 404);
        }

        return response([
            "message" => "Berhasil melakukan update Cast id : $id"
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cast = Casts::find($id);

        if (!$cast) {
            return response([
                "message" => "Data Cast tidak dapat ditemukan"
            ], 404);
        }

        $cast->delete();

        return response([
            "message" => "data dengan id : $id berhasil terhapus"
        ], 200);
    }
}
