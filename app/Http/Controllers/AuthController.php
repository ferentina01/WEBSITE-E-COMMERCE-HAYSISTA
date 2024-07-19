<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\CustomerAddress;
use App\Models\OrderItem;
use App\Models\Province;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('front.account.login');
    }

    public function register()
    {
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

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
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

    public function profile()
    {
        $userId = Auth::user()->id;
        $provinces = Province::orderBy('name', 'ASC')->get();
        $user = User::where('id', Auth::user()->id)->first();

        $address = CustomerAddress::where('user_id', $userId)->first();
        return view('front.account.profile', [
            'user' => $user,
            'provinces' => $provinces,
            'address' => $address
        ]);
    }

    public function updateProfile(Request $request)
    {
        $userId = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $userId . ',id',
            'phone' => 'required'
        ]);



        if ($validator->passes()) {
            $user = User::find($userId);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->save();

            // Set flash message
            session()->flash('success', 'Profil Berhasil Diupdate');

            // Return JSON response
            return response()->json([
                'status' => true,
                'message' => 'Profil berhasil diupdate'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }


    public function updateAddress(Request $request)
    {
        $userId = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|min:5',
            'last_name' => 'required',
            'email' => 'required|email',
            'province_id' => 'required',
            'address' => 'required',
            'city' => 'required',
            'subdistrict' => 'required',
            'zip' => 'required',
            'mobile' => 'required'
        ]);



        if ($validator->passes()) {
            // $user = User::find($userId);
            // $user->name = $request->name;
            // $user->email = $request->email;
            // $user->phone = $request->phone;
            // $user->save();

            CustomerAddress::updateOrCreate(
                ['user_id' => $userId],
                [
                    'user_id' => $userId,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'mobile' => $request->mobile,
                    'province_id' => $request->province_id,
                    'address' => $request->address,
                    'apartment' => $request->apartment,
                    'city' => $request->city,
                    'subdistrict' => $request->subdistrict,
                    'zip' => $request->zip,
                ]
            );


            // Set flash message
            session()->flash('success', 'Profil Berhasil Diupdate');

            // Return JSON response
            return response()->json([
                'status' => true,
                'message' => 'Profil berhasil diupdate'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }





    public function logout()
    {
        Auth::logout();
        return redirect()->route('account.login')
            ->with('success', 'Kamu berhasil keluar');
    }

    public function orders()
    {
        $user = Auth::user();

        $orders = Order::where('user_id', $user->id)->orderBy('created_at', 'DESC')->get();

        $data['orders'] = $orders;
        return view('front.account.order', $data);
    }

    public function orderDetail($id)
    {
        $data = [];
        $user = Auth::user();

        $order = Order::where('user_id', $user->id)->where('id', $id)->first();
        $data['order'] = $order;

        $orderItems = OrderItem::where('order_id', $id)->get();
        $data['orderItems'] = $orderItems;

        $orderItemsCount = OrderItem::where('order_id', $id)->count();
        $data['orderItemsCount'] = $orderItemsCount;

        return view('front.account.order-detail', $data);
    }

    public function wishlist()
    {
        $wishlists = Wishlist::where('user_id', Auth::user()->id)->with('product')->get();

        $data = [];
        $data['wishlists'] =  $wishlists;
        return view('front.account.wishlist', $data);
    }

    public function removeProductFromWishList(Request $request)
    {

        $wishlist = Wishlist::where('user_id', Auth::user()->id)->where('product_id', $request->id)->first();

        if ($wishlist == null) {
            session()->flash('error', 'Produk sudah dihapus.');
            return response()->json([
                'status' => true,
            ]);
        } else {

            Wishlist::where('user_id', Auth::user()->id)->where('product_id', $request->id)->delete();

            session()->flash('success', 'Produk wishlist Berhasil Dihapus.');
            return response()->json([
                'status' => true,
            ]);
        }
    }

    public function showchangePasswordForm()
    {
        return view('front.account.change-password');
    }

    public function changePassword(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'old_password' => 'required',
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validator->passes()) {
            $user = User::select('id','password')->where('id',Auth::user()->id)->first();


            if (!Hash::check($request->old_password, $user->password)) {
                session()->flash('error', 'Kata sandi lama Anda salah, silakan coba lagi.');
                return response()->json([
                    'status' => true,
                    
                ]);
            }

            
            User::where('id', $user->id)->update([
                'password' => Hash::make($request->new_password)

            ]);

            session()->flash('success', 'Anda telah berhasil mengubah kata sandi anda.');
            
            return response()->json([
                'status' => true,
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    
    public function forgotPassword() {
        return view('front.account.forgot-password');

    }
    
    public function processForgotPassword (Request $request) { 
        $validator = Validator::make($request->all(),[
             'email' => 'required|email|exists:users, email'
        ]);

        if ($validator->fails()) {
            return redirect()->route('front.forgotPassword')->withInput()->withErrors($validator);

        }
    }

}
