<?php
 
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
 
 
class AuthController extends Controller
{
 
    public function register(Request $request)
    {
 
        $email = $request->input("email");
        $password = $request->input("password");
 
        $hashPwd = Hash::make($password);
 
        $data = [
            "email" => $email,
            "password" => $hashPwd
        ];

        $roleid = DB::table('role')->where('role_name', "Admin")->pluck('role_id');
        $query  = DB::table('user')->insert(
            [
                'email' => $data['email'],
                'password' => $data['password'],
                'role_id'  => $roleid[0]
            ]
        );

        if ($query){
            $out = [
                "message" => "register_success",
                "code"    => 201,
            ];
        } else {
            $out = [
                "message" => "vailed_regiser",
                "code"   => 404,
            ];
        }
        return response()->json($out, $out['code']);
    }
 
    public function login(Request $request)
    {
        $email = $request->input("email");
        $password = $request->input("password");

        $user = DB::table('user')->where("email", $email)->first();

        if (!$user) {
            $out = [
                "message" => "login_failed",
                "code"    => 401,
                "result"  => [
                    "token" => null,
                ]
            ];
            return response()->json($out, $out['code']);
        }
        if (Hash::check($password, $user->password)) {
            $newtoken  = $this->generateRandomString();
            
            $query  = DB::table('user')
              ->where('user_id', $user->user_id)
              ->update(['token' => $newtoken]);
 
            $out = [
                "message" => "login_success",
                "code"    => 200,
                "result"  => [
                    "token" => $newtoken,
                ]
            ];
        } else {
            $out = [
                "message" => "login_failed",
                "code"    => 401,
                "result"  => [
                    "token" => null,
                ]
            ];
        }
        
        return response()->json($out, $out['code']);

    }
 
    function generateRandomString($length = 20)
    {
        $karakkter = '012345678dssd9abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $panjang_karakter = strlen($karakkter);
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= $karakkter[rand(0, $panjang_karakter - 1)];
        }
        return $str;
    }

    public function Logout(){
        $out    = [
            "status" => true,
        ];
        return response()->json($out,200);
    }
}