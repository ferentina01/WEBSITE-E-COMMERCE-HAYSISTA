<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
// use App\Http\Controllers\SubCategory;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use App\Models\TempImage;
use App\Models\ProductImage;
// use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\File;
use App\Models\SubCategory;





class ProductController extends Controller
{
    public function index(Request $request){

        $products = Product::latest('id')->with('product_images');

        if($request->get('keyword') !=""){
            $products = $products->where('title','like','%'.$request->keyword.'%');
        }

        $products = $products->paginate(20);
             //dd($products);
        $data['products'] = $products;
        return view('admin.products.list', $data);
        
        // // $products = Product::with('product_images')->get();
        // return view('admin.products.list', compact('products'));

    }

    public function create(){
        $data = [];
        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();
        $data['categories'] = $categories; 
        $data['brands'] = $brands; 
        $data['productImages'] = collect(); 

        return view('admin.products.create', $data);

    }

    public function store(Request $request)
    {
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products',
            'track_qty' => 'required|in:Yes,No',
            'category_id' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
        ];

        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {
            $product = new Product;
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->category_id = $request->category_id;
            $product->sub_category_id = $request->sub_category_id;
            $product->brand_id = $request->brand;
            $product->is_featured = $request->is_featured;
            $product->shipping_returns = $request->shipping_returns;
            $product->short_description = $request->short_description;
            $product->related_products = (!empty($request->related_products)) ? implode(',', $request->related_products) : '';

            $product->save();

            $formattedPrice = 'Rp ' . number_format($product->price, 0, ',', '.');

            // Save gallery
            if (!empty($request->image_array)) {
                foreach ($request->image_array as $temp_image_id) {
                    // Ambil info gambar sementara dari database
                    $tempImageInfo = TempImage::find($temp_image_id);
                    $extArray = explode('.', $tempImageInfo->name);
                    $ext = last($extArray);

                    // Simpan info gambar ke dalam database
                    $productImage = new ProductImage();
                    $productImage->product_id = $product->id;
                    $productImage->image = 'NULL';
                    $productImage->save();

                    $imageName = $product->id . '-' . $productImage->id . '-' . time() . '.' . $ext;
                    

                    $sourcePath = public_path('/temp/thumb/') . '/' . $tempImageInfo->name;
                    $destPath = public_path('/uploads/product') . '/' . $imageName;
                    File::move($sourcePath, $destPath);
                    

                    $productImage->image = $imageName;
                    $productImage->save();

                }
            }


            session()->flash('success', 'Produk berhasil ditambahkan');
            return response()->json([
                'status' => true,
                'message' => 'Produk berhasil ditambahkan'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($id, Request $request){


        $product = Product::find($id);

        if(empty($product)){
            return redirect()->route('products.index')->with('error','Product Tidak Ditemukan');
        }
        //dd($product->description);

        //fetch product images
        $productImages = ProductImage::where('product_id',$product->id)->get();
        $subCategories = SubCategory::where('category_id',$product->category_id)->get();

        //fetch related product
        $relatedProducts = [];
        if($product->related_products != ''){
            $productArray = explode(',',$product->related_products);
            $relatedProducts = Product::whereIn('id',$productArray)->with('product_images')->get();
        }

        $data = [];
        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();
        $data['categories'] = $categories;
        $data['brands'] = $brands;
        $data['product'] = $product;
        $data['subCategories'] = $subCategories;
        $data['productImages'] = $productImages;
        $data['relatedProducts'] = $relatedProducts;

        
        return view('admin.products.edit', $data);

    }

    public function update($id, Request $request){
        $product = Product::find($id);

        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products,slug,'.$product->id.',id',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products,sku,'.$product->id.',id',
            'track_qty' => 'required|in:Yes,No',
            'category_id' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
        ];

        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->category_id = $request->category_id;
            $product->sub_category_id = $request->sub_category_id;
            $product->brand_id = $request->brand;
            $product->is_featured = $request->is_featured;
            $product->shipping_returns = $request->shipping_returns;
            $product->short_description = $request->short_description;
            $product->related_products = (!empty($request->related_products)) ? implode(',',$request->related_products): '';
            $product->save();

            $formattedPrice = 'Rp ' . number_format($product->price, 0, ',', '.');

            // Save gallery
            

            session()->flash('success', 'Produk berhasil diedit');
            return response()->json([
                'status' => true,
                'message' => 'Produk berhasil diedit'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($id, Request $request){
        $product = Product::find($id);

        if (empty($product)) {
            session()->flash('error', 'Produk tidak ditemukan');

            return response()->json([
                'status' => false,
                'notFound' => true,
            ]);
        }

        $productImages = ProductImage::where('product_id',$id)->get();

        if (!empty($productImages)) {
            foreach ($productImages as $productImage) {
                File::delete(public_path('/uploads/product/') . $productImage->image);
            }


            $productImage::where('product_id',$id)->delete();
        }
            $product->delete();

            session()->flash('success', 'Produk berhasil dihapus');

            
                return response()->json([
                    'status' => true,
                    'message' => 'Produk berhasil dihapus',
                ]);
            
            

        }

        // File::delete(public_path('/uploads/product') . '/' . $$product->image);
        
        public function getProducts(Request $request){

            $tempProduct = [];
            if($request->term != ""){
                $products = Product::where('title', 'like', '%'.$request->term.'%')->get();
            }

            if($products != null){

                foreach($products as $product){
                    $tempProduct[] = array('id' => $product->id, 'text' => $product->title);
                }
            }

            return response()->json([
                'tags'=> $tempProduct,
                'status'=> true
            ]);

        }

    }
    

    



