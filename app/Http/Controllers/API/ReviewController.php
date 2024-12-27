<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reviews;

class ReviewController extends Controller
{
    public function storeupdate(Request $request)
    {
        $request->validate([
            'critic' => 'required',
            'rating' => 'required',
            'movie_id' => 'required|exists:movies,id'
        ], [
            'required' => 'inputan :attribute harus diisi',
            'exists' => 'inputan :attribute tidak ditemukan di table movies',
        ]);

        $user = auth()->user();

        Reviews::updateOrCreate(
            ['user_id' => $user->id],
            [
                'critic' => $request->input('critic'),
                'rating' => $request->input('rating'),
                'movie_id' => $request->input('movie_id')
            ]
        );

        return response([
            "message" => "Review berhasil dibuat/diupdate"
        ], 201);
    }
}
