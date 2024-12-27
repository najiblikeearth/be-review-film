<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Casts_Movie;

class CastMovieController extends Controller
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
        $castMovie = Casts_Movie::all();

        return response([
            "message" => "Berhasil Tampil cast Movie",
            "data" => $castMovie
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:2',
            'cast_id' => 'required|exists:casts,id',
            'movie_id' => 'required|exists:movies,id'
        ], [
            'required' => 'inputan :attribute harus diisi tidak boleh kosong.',
            'min' => 'inputan :attribute harus :min karakter',
            'exists' => 'inputan :attribute tidak ditemukan di table movies'
        ]);

        $castMovie = new Casts_Movie;

        $castMovie->name = $request->input('name');
        $castMovie->cast_id = $request->input('cast_id');
        $castMovie->movie_id = $request->input('movie_id');

        $castMovie->save();

        return response([
            "message" => "Berhasil tambah cast Movie"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $castMovie = Casts_Movie::with(['movie', 'cast'])->find($id);
        if (!$castMovie) {
            return response([
                "message" => "Data Cast Movie tidak ditemukan"
            ], 404);
        }

        return response([
            "message" => "Berhasil Tampil Detail Cast Movie",
            "data" => $castMovie
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|min:2',
            'cast_id' => 'required|exists:casts,id',
            'movie_id' => 'required|exists:movies,id'
        ], [
            'required' => 'inputan :attribute harus diisi tidak boleh kosong.',
            'min' => 'inputan :attribute harus :min karakter',
            'exists' => 'inputan :attribute tidak ditemukan di table movies'
        ]);

        $castMovie = Casts_Movie::find($id);
        if (!$castMovie) {
            return response([
                "message" => "Data cast Movie tidak ditemukan"
            ], 404);
        }

        $castMovie->name = $request->input('name');
        $castMovie->cast_id = $request->input('cast_id');
        $castMovie->movie_id = $request->input('movie_id');

        $castMovie->save();

        return response([
            "message" => "Berhasil Update cast Movie"
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $castMovie = Casts_Movie::find($id);

        if (!$castMovie) {
            return response([
                "message" => "Data Cast Movie tidak ditemukan"
            ], 404);
        }

        $castMovie->delete();

        return response([
            "message" => "Berhasil Delete cast Movie"
        ], 200);
    }
}
