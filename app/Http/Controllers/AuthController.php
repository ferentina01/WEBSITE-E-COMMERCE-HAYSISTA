<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(){
        return view('front.account.login');

    }

    public function register(){
        return view('front.account.register');

    }


    public function processRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5|confirmed'
        ]);

        if ($validator->passes()) {
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = bcrypt($request->password); // Always hash passwords before saving
            $user->save();

            session()->flash('success', 'Akun kamu berhasil didaftarkan');

            return response()->json([
                'status' => true,
                'redirect' => route('account.login')
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function authenticate(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required',

        ]);
        // if ($validator->passes()){

        //     if(Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))){
        //             return redirect()->route('account.profile');

        //         if (session()->has('url.intended')) {
        //             return redirect(session()->get('url.intended'));
        //         }
        //         return redirect()->route('account.profile');

        //     }else{
        //     //session()->flash('error', 'email/sandi anda salah' );
        //     return redirect()->route('account.login')
        //     ->withInput($request->only('email'))
        //     ->with('error', 'email/sandi anda salah' );

        // }
        // }else{
        //     return redirect()->route('account.login')
        //     ->withErrors($validator)
        //     ->withInput($request->only('email'));
        // }
        if ($validator->passes()) {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
                // Jika ada intended URL, arahkan ke intended URL
                if (session()->has('url.intended')) {
                    $intendedUrl = session()->get('url.intended');
                    session()->forget('url.intended'); // Hapus intended URL setelah digunakan
                    return redirect($intendedUrl);
                }

                // Jika tidak ada intended URL, arahkan ke halaman profil
                return redirect()->route('account.profile');
            } else {
                return redirect()->route('account.login')
                ->withInput($request->only('email'))
                    ->with('error', 'Email/sandi Anda salah.');
            }
        } else {
            return redirect()->route('account.login')
            ->withErrors($validator)
                ->withInput($request->only('email'));
        }


    }

    public function profile(){
        return view('front.account.profile');

    }

    public function logout(){
        Auth::logout();
        return redirect()->route('account.login')
        ->with('success', 'Kamu berhasil keluar');
    }

}