<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\OrderItem;
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
        return view('front.account.profile');
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
}
