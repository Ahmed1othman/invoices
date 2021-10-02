<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function getLogin(){

        return view('auth.signin');
    }

    public function login(LoginRequest $request){
        try {
            $remember_me = $request->has('remember_me')?true:false;
            if(auth()->guard('admin')->attempt([
                'email'=>$request->input('email'),
                'password'=>$request->input('password'),
            ],$remember_me)){
                $request->session()->regenerate();
                //if (auth()->guard('admin')->user()->Status=='مفعل')
                    return redirect()->route('admin.dashboard');
            }
            return  redirect()->back()->with(['error'=>'اليوزر نيم او الباسورد غير صحيح']);

        }catch (\Exception $ex){
            return $ex->getMessage();
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
