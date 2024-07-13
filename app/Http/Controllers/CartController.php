<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use League\CommonMark\Extension\CommonMark\Node\Inline\Strong;

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
                Cart::add($product->id, $product->title,1,$product->price,
                    ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : '']
                );
                $status = true;
                $message = '<Strong>' . $product->title . '</Strong> sukses dimasukkan kedalam keranjangmu';
                session()->flash('Sukses', $message);
            }else{
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
            $message = '<Strong>'.$product->title . '</Strong> sukses dimasukkan kedalam keranjangmu';
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

    public function updateCart(Request $request){
        $rowId = $request->rowId;
        $qty = $request->qty;


        $itemImfo= Cart::get($rowId);
        $product = Product::find($itemImfo->id);

        

        if($product->track_qty == 'Yes'){
            if($qty <= $product->qty){
                Cart::update($rowId, $qty);
                $message = 'Keranjang Berhasil Di updated';
                $status = true;
            }else{
                $message = 'Jumlah stok hanya tersisa ('.$product->qty.') Mohon memesan tidak lebih dari itu';
                $status = false;
            }

        }else{
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

    public function deleteItem(Request $request){
         
        $itemImfo = Cart::get($request->rowId);

        if($itemImfo == null){
            $errorMessage = 'Produk tidak ditemukan dalam keranjang';
            session()->flash('error',$errorMessage);
            return response()->json([
                'status' =>false,
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
}
