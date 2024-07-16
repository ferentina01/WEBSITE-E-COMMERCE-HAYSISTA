<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use App\Models\DiscountCoupon;


class DiscountCodeController extends Controller
{
    public function index(Request $request){
        // $discountCoupons = DiscountCoupon::orderBy('id', 'asc');

        // // Jika ada keyword pencarian yang diberikan melalui request, tambahkan kondisi pencarian
        // if (!empty($request->get('keyword'))) {
        //     // Tangkap nilai keyword dari request dan buat format pencarian
        //     $keyword = '%' . $request->get('keyword') . '%';
        //     // Tambahkan kondisi where untuk mencocokkan nama kupon dengan keyword yang diberikan
        //     $discountCoupons->where('name', 'like', $keyword);
        // }
        // $discountCoupons = $discountCoupons->paginate(10);

        // // Kembalikan view 'admin.coupon.list' sambil mengirimkan data discountCoupons ke view tersebut
        // return view('admin.coupon.list', compact('discountCoupons'));

        
        $discountCoupons = DiscountCoupon::latest();
        if (!empty($request->get('keyword'))) {
            $discountCoupons = $discountCoupons->where('name', 'like', '%' . $request->get('keyword'). '%');
        }
        $discountCoupons = $discountCoupons->paginate(10);
        return view('admin.coupon.list', compact('discountCoupons'));

    }


    public function create(){
        return view('admin.coupon.create');
    }

            
    public function store (Request $request) {
        //  dd($request->all()); 
        $validator = Validator::make($request->all(), [
                'code' => 'required',
                'type' => 'required',
                'discount_amount' => 'required|numeric',
                'status' => 'required',
                 'name' => 'required|string|max:255',
         ]);
        
            if ($validator->passes()) {

                
                // tanggal mulai harus lebih besar dari tanggal sekarang
                if (!empty($request->starts_at)) {
                    $now = Carbon::now();
                    $startAt = Carbon::createFromFormat('Y-m-d H:i:s',$request->starts_at);
                
                    if ($startAt->lte($now) == true) {
                        return response()->json([
                            'status' => false,
                            'errors' => ['starts_at' => 'Tanggal mulai tidak boleh kurang dari waktu saat ini']
                        ]);

                    }
                }

                //tanggal kadaluarsa harus lebih besar dari tanggal mulai
                if (!empty($request->starts_at) && !empty($request->expires_at)) {
                    $expiresAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->expires_at);
                    $startAt =  Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);
                

                    if ($expiresAt->gt($startAt) == false) {
                        return response()->json([

                            'status' => false,
                            'errors' => ['expires_at' => 'Tanggal kadaluarsa harus lebih besar dari tanggal mulai']
                        ]);
                    }
                }

                
                $discountCode = new DiscountCoupon(); 
                $discountCode->code = $request->code;
                $discountCode->name = $request->name;
                $discountCode->description = $request->description;
                $discountCode->max_uses = $request->max_uses; 
                $discountCode->max_uses_user = $request->max_uses_user;
                $discountCode->type = $request->type;
                $discountCode->discount_amount = $request->discount_amount;
                $discountCode->min_amount = $request->min_amount;
                $discountCode->status = $request->status; 
                $discountCode->starts_at = $request->starts_at;
                $discountCode->expires_at = $request->expires_at;
                $discountCode->save();

                $message = 'Kupon Diskon Berhasil Ditambahkan';
                session()->flash('succes',$message);

                return response()->json([
                    'status' => true,
                    'message' =>$message 
                ]);

            } else {
            return response()->json([
            'status' => false,
            'errors' => $validator->errors()

            ]);

            }
    }

    public function edit(Request $request, $id){


        $coupon = DiscountCoupon::find($id);

        return view('admin.coupon.edit');

    }
    
}