<?php

use App\Mail\OrderEmail;
use App\Models\Category;
use App\Models\Order;
use App\Models\ProductImage;
use App\Models\Province;
use Illuminate\Support\Facades\Mail;

function getCategories(){
    return Category::orderBy('name','ASC')->with('sub_category')->orderBy('id','DESC')->where('status',1)->where('showHome','Yes')->get();

}

function getProductImage($productId){
    return ProductImage::where('product_id',$productId)->first();
}

function OrderEmail($orderId, $userType="customer"){
    $order = Order::where('id',$orderId)->with('items')->first();

    if($userType == 'customer'){
        $subject = 'Terimakasih telah membeli';
        $email = $order->email;
    }else{

        $subject = 'Kamu Punya Pesanan';
        $email = env('ADMIN_EMAIL');

    }    

    $mailData = [
        'subject'=> $subject,
        'order' => $order,
        'userType' => $userType

    ];
    

    Mail::to($email)->send(new OrderEmail($mailData));

    //dd($order);
}

function getProvinceInfo($id)
{
    return Province::where('id', $id)->first();
}

?>