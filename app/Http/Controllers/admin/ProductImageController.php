<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

use App\Models\ProductImage;


class ProductImageController extends Controller
{
    public function update(Request $request)
    {

        $image = $request->image;
        $ext = $image->getClientOriginalExtension();
        $sourcePath = $image->getPathName();


        $productImage = new ProductImage();
        $productImage->product_id = $request->product_id;
        $productImage->image = 'NULL';
        $productImage->save();

        $imageName = $request->product_id . '-' . $productImage->id . '-' . time() . '.' . $ext;
        $destPath = public_path('/uploads/product') . '/' . $imageName;
        File::move($sourcePath, $destPath);

        $productImage->image = $imageName;
        $productImage->save();


        return response()->json([
            'status' =>true,
            'image_id' => $productImage->id,
            'ImagePath' => asset('uploads/product/' . $productImage->image),
            'message' =>'foto sukses di simpan'
        ]);
    }

    public function destroy(Request $request){
        $productImage = ProductImage::find($request->id);

        if(empty($productImage)){
            return response()->json([
                'status' => False,
                'message' => 'foto tidak ditemukan '
            ]);
        }

       // File::delete(public_path('/uploads/product') . '/' . $$productImage->image);
        File::delete(public_path('/uploads/product').'/'.$productImage->image);

       $productImage->delete();

        return response()->json([
            'status' => true,
            'message' => 'Foto sukses dihapus'
        ]);
    }
}
