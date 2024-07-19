<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SettingController extends Controller
{
    public function showchangePasswordForm()
    {
        return view('admin.change-password');
    }

    public function processChangePassword(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password',
        ]);

        $id = Auth::guard('admin')->user()->id;

        $admin = User::where('id', $id)->first();

        if ($validator->passes()) {


            if (!Hash::check($request->old_password, $admin->password)) {
                session()->flash('error', 'Kata sandi lama Anda salah, silakan coba lagi.');
                return response()->json([
                    'status' => true

                ]);
            }
            User::where('id',$id)->update([
                'password' => Hash::make($request->new_password)
            ]);

            session()->flash('success', 'Anda telah berhasil mengubah kata sandi anda');
            return response()->json([
                'status' => true
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
}