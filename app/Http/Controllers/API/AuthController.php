<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Roles;
use App\Models\Users;

use Illuminate\support\Facades\Hash;

use Illuminate\Support\Facades\Mail;
use App\Mail\UserRegisterMail;
use App\Mail\GenerateEmailMail;
use App\Models\OtpCode;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|min:2',
            'email' => 'required|email|unique:users,id',
            'password' => 'required|min:8',
        ], [
            'required' => "inputan :attribute harus diisi",
            'min' => "inputan :attribute minimal :min karakter",
            'email' => "inputan :attribute harus berformat email",
            'unique' => "inputan email sudah terdaftar",
            'confirmed' => "inputan password beda dengan konfirmasi password",

        ]);

        $user = new Users;

        $roleUser = Roles::where('name', 'user')->first();

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->role_id = $roleUser->id;

        $user->save();

        Mail::to($user->email)->send(new UserRegisterMail($user));

        return response([
            "message" => "Register Berhasil",
            "user" => $user->only(['id', 'name', 'email', 'role_id'])
        ], 201);
    }

    public function login(Request $request)
    {

        $request->validate([

            'email' => 'required',
            'password' => 'required',
        ], [
            'required' => "inputan :attribute harus diisi"
        ]);

        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'User Invalid'], 401);
        }

        $UserData = Users::with('profile', 'role')->where('email', $request->input('email'))->first();

        return response([
            "message" => "Berhasil login",
            "user" => $UserData,
            "token" => $token
        ], 200);
    }

    public function currentuser()
    {
        $user = auth()->user();
        return response()->json([
            'message' => "Berhasil get user",
            'user' => $user
        ]);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json([
            'message' => "Logout berhasil"
        ]);
    }

    public function generateOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'

        ], [
            'required' => "inputan :attribute harus diisi",
            'email' => "inputan harus berformat email"
        ]);

        $user = Users::where('email', $request->input('email'))->first();

        $user->generate_otp();

        Mail::to($user->email)->send(new GenerateEmailMail($user));

        return response()->json([
            'success' => true,
            'message' => "OTP Code Berhasil di generate"
        ]);
    }

    public function verifikasi(Request $request)
    {
        $request->validate([
            'otp' => 'required|min:6|max:6'

        ], [
            'required' => "inputan :attribute harus diisi",
            // 'integer' => "inputan :attribute harus diisi angka",
            'min' => "inputan minimal :min karakter",
            'max' => "inputan maksimal :max karakter"

        ]);

        $user = auth()->user();

        //Jika Otp Code tidak ditemukan
        $otp_code = OtpCode::where('otp', $request->input('otp'))->where('user_id', $user->id)->first();

        if (!$otp_code) {
            return response([
                "response_code" => "01",
                "response_message" => "OTP Code tidak ditemukan"
            ], 400);
        }

        //JIka valid until melebihi waktu sekarang
        $now = Carbon::now();
        if ($now > $otp_code->valid_until) {
            return response([
                "response_code" => "01",
                "response_message" => "otp code sudah tidak berlaku, silahkan generate ulang"
            ], 400);
        }


        // update user
        $user = User::find($otp_code->user_id);

        $user->email_verified_at_stamp = $now;

        $user->save();

        $otp_code->delete();

        return response([
            "response_code" => "00",
            "response_message" => "email sudah terverifikasi"
        ], 200);
    }
}
