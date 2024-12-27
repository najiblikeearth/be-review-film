<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Genres;

class GenreController extends Controller
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
        $genre = Genres::all();

        return response([
            "message" => "Tampil data berhasil",
            "data" => $genre
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:2'
        ], [
            'required' => 'inputan :attribute harus diisi'
        ]);

        $genre = new Genres;

        $genre->name = $request->input('name');

        $genre->save();

        return response([
            "message" => "Tambah Genre berhasil"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $genre = Genres::with('listMovies')->find($id);
        if (!$genre) {
            return response([
                "message" => "Data Genre tidak ditemukan"
            ], 404);
        }

        return response([
            "message" => "Berhasil Detail data dengan id $id",
            "data" => $genre
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

        $genre = Genres::find($id);
        if (!$genre) {
            return response([
                "message" => "Data Genre tidak ditemukan"
            ], 404);
        }

        $genre->name = $request->input('name');

        $genre->save();

        return response([
            "message" => "Berhasil melakukan update Genre id : $id"
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $genre = Genres::find($id);

        if (!$genre) {
            return response([
                "message" => "Data Genre tidak ditemukan"
            ], 404);
        }

        $genre->delete();

        return response([
            "message" => "data dengan id : $id berhasil terhapus"
        ], 200);
    }
}
