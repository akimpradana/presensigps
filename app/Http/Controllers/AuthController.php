<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Notifications\HasApiTokens;

class AuthController extends Controller
{
    public function proseslogin(Request $request){
        if(auth::guard('karyawan')-> attempt(['nik' => $request ->nik, 'password'=>$request->password])){
            return redirect('/dashboard');
        }else{
            return redirect('/')->with(['warning'=>'Nik/Password Salah']);
        }
    }

    public function proseslogout(){
        if (auth::guard('karyawan')->check());{
            auth::guard('karyawan')->logout();
            return redirect('/');
        }
    }
    public function proseslogoutadmin(){
        if (auth::guard('user')->check());{
            auth::guard('user')->logout();
            return redirect('/panel');
        }
    }
    public function prosesloginadmin(Request $request){
        if(auth::guard('user')-> attempt(['email' => $request ->email, 'password'=>$request->password])){
            return redirect('/panel/dashboardadmin');
        }else{
            return redirect('/panel')->with(['warning'=>'username atau Password Salah']);
        }
    }
}
