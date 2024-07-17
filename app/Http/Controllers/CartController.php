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
use App\Models\Shipping;
use App\Models\ShippingCharge;
use Illuminate\Validation\Rules\Can;
use Illuminate\Support\Carbon;
use App\Models\DiscountCoupon;



// Illuminate\Support\Facades\Log;


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
        $discount = 0;

        //if keranjang kosong alihkan ke halaman keranjang
        if (Cart::count() == 0) {
            return redirect()->route('front.cart');
        }

        if (Auth::check() == false) {
            session()->put('url.intended', route('front.checkout'));
            return redirect()->route('account.login');
        }

        // Ambil alamat pelanggan jika sudah tersimpan
        $customerAddress = CustomerAddress::where('user_id', Auth::user()->id)->first();
        session()->forget('url.intended');

        // Ambil daftar provinsi
        $province = Province::orderBy('name', 'ASC')->get(); // Mengambil data dari tabel 'province'

        // // Inisialisasi variabel totalShippingCharge dan grandTotal
        // $grandTotal = 0;
        $subTotal = Cart::subtotal(0, '.', '');
        // //apply diskon 
        if (session()->has('code')) {
            $code = session()->get('code');

            if ($code->type == 'percent') {
                $discount = ($code->discount_amount / 100) * $subTotal;
            } else {
                $discount = $code->discount_amount;
            }
        }


        //kalkulasi pengiriman
        if ($customerAddress != '') {
            $userProvince = $customerAddress->province_id;
            $shippingInfo = ShippingCharge::where('province_id', $userProvince)->first();

            $totalQty = 0;
            $totalShippingCharge = 0;
            $grandTotal = 0;

            foreach (Cart::content() as $item) {
                $totalQty += $item->qty;
            }

            $totalShippingCharge = $totalQty * $shippingInfo->amount;
            $grandTotal = ($subTotal - $discount) + $totalShippingCharge;
            // if ($shippingInfo) {
            //     $totalShippingCharge = $totalQty * $shippingInfo->amount;
            // }

            // $grandTotal = ($subTotal - $discount) + $totalShippingCharge;


        } else {
            $grandTotal = ($subTotal - $discount);
            $totalShippingCharge = 0;
        }


        return view('front.checkout', [
            'province' => $province,
            'customerAddress' => $customerAddress,
            'totalShippingCharge' => $totalShippingCharge,
            'discount' => $discount,
            'grandTotal' => $grandTotal,


        ]);
    }

    //


    public function processCheckout(Request $request)
    {


        // Step - 1 Apply Validation
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|min:5',
            'last_name' => 'required',
            'email' => 'required|email',
            'province' => 'required',
            'address' => 'required|min:30',
            'city' => 'required',
            'subdistrict' => 'required',
            'zip' => 'required',
            'mobile' => 'required'
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
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'province_id' => $request->province,
                'address' => $request->address,
                'apartment' => $request->apartment,
                'city' => $request->city,
                'subdistrict' => $request->subdistrict,
                'zip' => $request->zip,
            ]
        );

        // step - 3 store data in orders table

        if ($request->payment_method == 'cod') {
            $discountCodeId = '';
            $promoCode = '';
            $shipping = 0;
            $discount = 0;
            $subTotal = Cart::subtotal(0, '.', '');
            // $grandTotal = $subTotal + $shipping;

            //apply diskon 
            if (session()->has('code')) {
                $code = session()->get('code');

                if ($code->type == 'percent') {
                    $discount = ($code->discount_amount / 100) * $subTotal;
                } else {
                    $discount = $code->discount_amount;
                }

                $discountCodeId = $code->id;
                $promoCode  = $code->code;
            }

            //calculate shipping  
            $shippingInfo = ShippingCharge::where('province_id', $request->province)->first();

            $totalQty = 0;
            foreach (Cart::content() as $item) {
                $totalQty += $item->qty;
            }

            if ($shippingInfo != null) {
                $shipping = $totalQty * $shippingInfo->amount;
                $grandTotal = ($subTotal-$discount) + $shipping;

               
            }

            
            $order = new Order;
            $order->subtotal = $subTotal;
            $order->shipping = $shipping;
            $order->grand_total = $grandTotal;
            $order->discount = $discount;
            $order->coupon_code_id = $discountCodeId;
            $order->coupon_code = $promoCode;
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
        }


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

        session()->forget('code');
        return response()->json([
            'message' => 'Pesanan sukses disimpan',
            'orderId' => $order->id,
            'status' => true,

        ]);
    }

    public function thankyou($orderId)
    {


        return view('front.thanks', [
            'orderId' => $orderId
        ]);
    }


    public function getOrderSummery(Request $request)
    {
        $subTotal = Cart::subtotal(2, '.', '');
        $discount = 0;
         $discountString = '';

        //apply diskon 
        if (session()->has('code')) {
            $code = session()->get('code');

            if ($code->type == 'percent') {
                $discount = ($code->discount_amount / 100) * $subTotal;
            } else {
                $discount = $code->discount_amount;
            }

            $discountString = '<div class="mt-4" id="discount-response">
            <strong> ' . session()->get('code')->code . ' </strong>
            <a class="btn btn-sm btn-danger" id="remove-discount"><i class="fa fa-times"></i></a>
        </div>';
        }




        if ($request->province_id > 0) {

            $shippingInfo = ShippingCharge::where('province_id', $request->province_id)->first();
            $totalQty = 0;
            foreach (Cart::content() as $item) {
                $totalQty += $item->qty;
            }


            if ($shippingInfo != null) {
                $shippingCharge = $totalQty * $shippingInfo->amount;
                $grandTotal = ($subTotal - $discount) + $shippingCharge;

                if ($grandTotal < 0) {
                    $grandTotal = 0;
                }


                return response()->json([
                    'status' => true,
                    'grandTotal' => number_format($grandTotal, 0, ',', '.'),
                    'discount' => $discount,
                     'discountString' =>  $discountString,
                    'shippingCharge' => number_format($shippingCharge, 0, ',', '.'),
                ]);
            }
        } else {

            // Jika $request->province_id <= 0 atau $shippingInfo == null
            return response()->json([
                'status' => true,
                'grandTotal' => number_format(($subTotal - $discount), 0, ',', '.'),
                'discount' => $discount,
                'discountString' =>  $discountString,
                'shippingCharge' => number_format(0, ',', '.'),
            ]);
        }
    }


    public function applyDiscount(Request $request)
    {
        //dd($request->code);


        $code = DiscountCoupon::where('code', $request->code)->first();

        if ($code == null) {
            return response()->json([

                'status' => false,
                'message' => 'Kupon diskon tidak valid',
            ]);
        }

        // Check if coupon start date is valid or not
        $now = Carbon::now();

        //echo $now->format('Y-m-d H:i:s');

        if ($code->starts_at != "") {
            $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $code->starts_at);

            if ($now->lt($startDate)) {
                return response()->json([
                    'status' => false,
                    'message' => ' diskon tidak valid',
                ]);
            }
        }

        if ($code->expires_at != "") {
            $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $code->expires_at);

            if ($now->gt($endDate)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Kupon diskon tidak valid',
                ]);
            }
        }

        //maks use chcek
        //     $couponUsed = Order::where('coupon_code_id', $code->id)->count();

        //     if ($couponUsed >= $code->max_uses){
        //         return response()->json([

        //         'status' => false,
        //         'message' => 'Kupon diskon telah habis',
        //     ]);

        //     //max uses use check

        // }
        //     $couponUsedByUser = Order::where(['coupon_code_id', $code->id, 'user_id' => Auth::user()->id])->count();

        //     if ($couponUsedByUser >= $code->max_uses_user) {
        //         return response()->json([

        //             'status' => false,
        //             'message' => 'Kamu telah menggunakan kupon ini',
        //         ]);
        //     }
        // Cek penggunaan kupon
        $couponUsed = Order::where('coupon_code_id', $code->id)->count();
        if ($couponUsed >= $code->max_uses) {
            return response()->json([
                'status' => false,
                'message' => 'Kupon diskon telah habis digunakan',
            ]);
        }

        // Cek penggunaan kupon oleh user
        $couponUsedByUser = Order::where('coupon_code_id', $code->id)
        ->where('user_id', Auth::user()->id)
        ->count();
        if ($couponUsedByUser >= $code->max_uses_user) {
            return response()->json([
                'status' => false,
                'message' => 'Kamu telah menggunakan kupon ini',
            ]);
        }

        session()->put('code', $code);
        return $this->getOrderSummery($request);
    }

    public function removeCoupon(Request $request)
    {
        session()->forget('code');
        return $this->getOrderSummery($request);
    }
}
