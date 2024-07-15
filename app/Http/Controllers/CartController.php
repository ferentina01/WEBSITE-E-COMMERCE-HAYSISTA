<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Province;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use League\CommonMark\Extension\CommonMark\Node\Inline\Strong;
use App\Models\CustomerAddress;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\OrderItem;


class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $product = Product::with('product_images')->find($request->id);

        if ($product == null) {
            return response()->json([
                'status' => false,
                'message' => 'Record not found'
            ]);
        }

        if (Cart::count() > 0) {
            //echo "Produk sudah ada di keranjang";

            $cartContent = Cart::content();
            $productAlreadyExist = false;

            foreach ($cartContent as $item) {
                if ($item->id == $product->id) {
                    $productAlreadyExist = true;
                }
            }
            if ($productAlreadyExist == false) {
                Cart::add(
                    $product->id,
                    $product->title,
                    1,
                    $product->price,
                    ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : '']
                );
                $status = true;
                $message = '<Strong>' . $product->title . '</Strong> sukses dimasukkan kedalam keranjangmu';
                session()->flash('Sukses', $message);
            } else {
                $status = false;
                $message = $product->title . ' Sudah ada di dalam keranjang';
            }
        } else {
            // echo "Keranjang kosong sekarang ada produk yang ditambahkan ke keranjang";
            Cart::add(
                $product->id,
                $product->title,
                1,
                $product->price,
                ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : '']
            );
            $status = true;
            $message = '<Strong>' . $product->title . '</Strong> sukses dimasukkan kedalam keranjangmu';
            session()->flash('Sukses', $message);
        }

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }


    public function cart()
    {

        $cartContent = Cart::content();
        //dd($cartContent);
        $data['cartContent'] = $cartContent;


        return view('front.cart', $data);
    }

    public function updateCart(Request $request)
    {
        $rowId = $request->rowId;
        $qty = $request->qty;


        $itemImfo = Cart::get($rowId);
        $product = Product::find($itemImfo->id);



        if ($product->track_qty == 'Yes') {
            if ($qty <= $product->qty) {
                Cart::update($rowId, $qty);
                $message = 'Keranjang Berhasil Di updated';
                $status = true;
            } else {
                $message = 'Jumlah stok hanya tersisa (' . $product->qty . ') Mohon memesan tidak lebih dari itu';
                $status = false;
            }
        } else {
            Cart::update($rowId, $qty);
            $message = 'Keranjang Berhasil Di updated';
            $status = true;
        }


        session()->flash($status ? 'Sukses' : 'error', $message);



        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    public function deleteItem(Request $request)
    {

        $itemImfo = Cart::get($request->rowId);

        if ($itemImfo == null) {
            $errorMessage = 'Produk tidak ditemukan dalam keranjang';
            session()->flash('error', $errorMessage);
            return response()->json([
                'status' => false,
                'message' => $errorMessage
            ]);
        }
        Cart::remove($request->rowId);

        $message = 'Produk Berhasil Dihapus dari Keranjang';
        session()->flash('Sukses', $message);
        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }

    public function checkout()
    {

        //if keranjang kosong alihkan ke halaman keranjang
        if (Cart::count() == 0) {
            return redirect()->route('front.cart');
        }


        //jika pengguna belum login arahkan ke halaman login
        if (Auth::check() == false) {
            if (!session()->has('url.intended')) {
                session(['url.intended' => url()->current()]);
            }
            return redirect()->route('account.login');
        }

        $customerAddress = CustomerAddress::where('user_id',Auth::user()->id)->first();
        session()->forget('url.intended');

        $province = Province::orderBy('name', 'ASC')->get(); // Mengambil data dari tabel 'province'
        return view('front.checkout', [
            'province' => $province, // Menetapkan variabel 'province' ke blade template        ]);
            'customerAddress' => $customerAddress 
        ]);
    }

    public function processCheckout(Request $request)
    {

        // step - 1 Apply Validation
        // $validator = Validator::make($request->all(), [
        //     'nama_depan' => 'required|min:5', 'nama_belakang' => 'required', 'email' => 'required|email', 'provinces' => 'required', 'alamat' => 'required|min:30', 'kota' => 'required', 'kecamatan' => 'required', 'kode_pos' => 'required', 'phone' => 'required'
        // ]);
        // Step - 1 Apply Validation
        $validator = Validator::make($request->all(), [

            'first_name' => 'required|min:5', 'last_name' => 'required', 'email' => 'required|email', 'province' => 'required', 'address' => 'required|min:30',
            'city' => 'required', 'subdistrict' => 'required', 'zip' => 'required', 'mobile' => 'required'
        ]);


        if ($validator->fails()) {
            return response()->json([
                'message' => 'Please fix the errors',
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
        


       
            // step 2 save user adddress //$customerAddress = CustomerAddress::find();
            $user = Auth::user();
            $customerAddress = CustomerAddress::updateOrCreate(
            ['user_id' => $user->id],
            [

                'user_id' => $user->id,
                'first_name' => $request->first_name, 'last_name' => $request->last_name, 'email' => $request->email, 'mobile' => $request->mobile, 'province_id' => $request->province, 'address' => $request->address,
                'apartment' => $request->apartment, 'city' => $request->city,
                'subdistrict' => $request->subdistrict, 'zip' => $request->zip,
            ]
        );
        // if ($customerAddress) {
        //     return response()->json([
        //         'message' => 'Address saved successfully',
        //         'status' => true
        //     ]);
        // } else {
        //     return response()->json([
        //         'message' => 'Failed to save address',
        //         'status' => false
        //     ]);
        // }

        
        // step - 3 store data in orders table
            if ($request->payment_method == 'cod') {
            $shipping = 0;
            $discount = 0;
            $subTotal = Cart::subtotal(2, '.','');
            $grandTotal = $subTotal+$shipping;

            $order = new Order;
            $order->subtotal = $subTotal;
            $order->shipping = $shipping;
            $order->grand_total = $grandTotal;
            $order->user_id = $user->id;
            $order->first_name = $request->first_name;
            $order->last_name = $request->last_name;
            $order->email = $request->email;
            $order->mobile = $request->mobile;
            $order->address = $request->address;
            $order->apartment = $request->apartment;
            $order->subdistrict = $request->subdistrict;
            $order->city = $request->city;
            $order->zip = $request->zip;
            $order->notes = $request->notes;
            $order->province_id = $request->province;
            $order->save();
   


        // step - 4 store order items in order items table 
        foreach (Cart::content() as $item) {
        $orderItem = new OrderItem;
        $orderItem->product_id = $item->id;
        $orderItem->order_id = $order->id;
        $orderItem->name = $item->name;
        $orderItem->qty = $item->qty;
        $orderItem->price = $item->price;
        $orderItem->total = $item->price * $item->qty;
        $orderItem->save();
        }

        session()->flash('succes', 'Kamu berhasil melakukan pesanan');

            Cart::destroy();
            return response()->json([
                'message' => 'Pesanan sukses disimpan',
                'orderId' => $order->id,
                'status' => true,
                
            ]);
        }else{

        }
    }

    public function thankyou($id){

        
        return view('front.thanks',[
            'id' => $id
        ]);
    }

}