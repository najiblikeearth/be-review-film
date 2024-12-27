<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profile;

class ProfileController extends Controller
{
    public function storeupdate(Request $request)
    {

        $request->validate([
            'age' => 'required|numeric',
            'biodata' => 'required',
            'adress' => 'required'
        ], [
            'required' => 'inputan :attribute harus diisi',
            'integer' => 'inputan :attribute harus berupa angka',
        ]);

        $user = auth()->user();

        $userData = Profile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'age' => $request->input('age'),
                'biodata' => $request->input('biodata'),
                'adress' => $request->input('adress')
            ]
        );

        return response([
            "message" => "Profile berhasil dibuat/diupdate"
        ], 201);
    }
}
