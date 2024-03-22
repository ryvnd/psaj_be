<?php

namespace App\Http\Controllers;

use App\Models\user_kk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserKkController extends Controller
{
    public function register(Request $req){
        try{
            $input = $req->all();
            if($req->has('password')){
                $password = $req->input('password');
                $input['password'] = Hash::make($password);
            }
            $email = $req->input('email');
            $cekemail = user_kk::where('email', $email)
                                ->count();
            if($cekemail > 0){
                return response()->json(['message'=>'Email sudah digunakan']);
            }else{
                $user = user_kk::create($input);
                return response()->json(['message'=>'Register berhasil', 'data'=>$user]);
            }
        }catch(\Throwable $e){
            return response()->json(['message' =>$e->getMessage()]);
        }
    }

    public function login(Request $req){
        try {
            $user = user_kk::where('email', $req->input('email'))->first();
            if ($user && Hash::check($req->input('password'), $user->password)) {
                return response()->json(['message' => 'Login berhasil', 'data' => $user]);
            } else {
                return response()->json(['message' => 'Email atau password salah']);
            }
        } catch(\Throwable $e) {
            return response()->json(['message' =>$e->getMessage()]);
        }
    }
}
