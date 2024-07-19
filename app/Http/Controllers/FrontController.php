<?php

namespace App\Http\Controllers;

use App\Mail\ContactEmail;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Wishlist;
use App\Models\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactEmil;
use App\Models\User;

class FrontController extends Controller
{
    public function index(){

        $products = Product::where('is_featured','Yes')->orderBy('id', 'DESC')->take(8)->where('status',1)->get();
        $data['featuredproducts'] = $products;


        $latestproducts = Product::orderBy('id', 'DESC')->where('status', 1)->take(8)->get();
        $data['latestproducts'] = $latestproducts;

        return view('front.home', $data);
        
    }

    public function addToWishList(Request $request){
        if(Auth::check() == false){

            session(['url.intended' => url()->previous()]);
            return response()->json([
                'status' => false
            ]);
        }

        $product = Product::where('id', $request->id)->first();

        if($product == null){
            return response()->json([
                'status' => true,
                'message' => '<div class="alert alert-danger">Produk tidak ditemukan</div>'
            ]);

        }

        
            Wishlist::updateOrCreate(
                [
                    'user_id' => Auth::user()->id, 
                    'product_id' => $request->id,
                ],
                [
                     'user_id' => Auth::user()->id, 
                     'product_id' => $request->id,
                ]
                );
        // $wishlist = new Wishlist;
        // $wishlist->user_id = Auth::user()->id;
        // $wishlist->product_id = $request->id;
        // $wishlist->save();


        return response()->json([
            'status' => true,
            'message' => '<div class="alert alert-success"><strong>' . $product->title . '</strong> berhasil ditambahkan ke wishlist.</div>'
        ]);

    }

    public function page($slug){
        $page = Page::where('slug',$slug)->first();
        if($page == null){
            abort(404);
        }

        return view('front.page',[
         'page' => $page
        //dd($page);
    ]);
    }

    public function sendContactEmail(Request $request){
        
            $validator = Validator::make($request->all(),[
            
                'name' => 'required',
                'email' => 'required|email', 
                'subject' => 'required|min:10'
            ]);

            if ($validator->passes()) {

                //send email
                                
                $mailData = [
                    'name' => $request->name, 
                    'email' => $request->email, 
                    'subject' => $request->subject, 
                    'message' => $request->message,
                    'mail_subject' => 'Kamu Menerima Kontak Email'
                ];

                $admin = User::where('id',3)->first();
                Mail::to($admin->email)->send(new ContactEmail($mailData));

                session()->flash('success', 'Trimakasih sudah menghubungi kami, untuk pertanyaan anda kami akan segera menghubungi anda secepatnya.');

                return response()->json([
                'status' => true,
                ]);
            } else{
                return response()->json([
                
                'status' => false,
                'errors' => $validator->errors()
                ]);
            }
        }
          
    
}

