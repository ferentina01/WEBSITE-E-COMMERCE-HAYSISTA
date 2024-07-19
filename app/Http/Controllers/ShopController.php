<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Brand;

class ShopController extends Controller
{
    public function index(Request $request, $categorySlug = null, $subCategorySlug = null ){

        $categorySelected = '';
        $subCategorySelected = '';
        $brandsArray = [];

        if(!empty($request->get('brand'))){
            $brandsArray = explode(',',$request->get('brand'));
        }

        // $categories = Category::orderBy('name','ASC')->with('sub_category')->where('status',1)->get();
        $categories = Category::orderBy('name', 'DESC')->with('sub_category')->where('status', 1)->get();
        $brands = Brand::orderBy('name','ASC')->where('status',1)->get();


        $products = Product::where('status',1);

        //apply filter

        if(!empty($categorySlug)){
            $category = Category::where('slug', $categorySlug)->first();
            $products = $products->where('category_id', $category->id);
            $categorySelected =  $category->id;
        }

        if (!empty($subCategorySlug)) {
            $subCategory = subCategory::where('slug', $subCategorySlug)->first();
            $products = $products->where('sub_category_id', $subCategory->id);
            $subCategorySelected = $subCategory->id;
        }

        if(!empty($request->get('brand'))){
            $brandsArray = explode(',',$request->get('brand'));
            $products = $products->whereIn('brand_id',$brandsArray);
        }

        

        if ($request->get('price_max') != '' && $request->get('price_min') != '') {
            if($request->get('price_max') == 5000000){
                $products = $products->whereBetween('price', [intval($request->get('price_min')),5000000]);

            }else{
                $products = $products->whereBetween('price', [intval($request->get('price_min')), intval($request->get('price_max'))]);

            }


        }
        if (!empty($request->get('search'))) {
            $products = $products->where('title', 'like', '%' . $request->get('search') . '%');
        }

        if ($request->get('sort') != ''){
            if($request->get('sort') == 'latest'){
                $products = $products->orderBy('id','DESC');
            } else if($request->get('sort') == 'price_asc'){
                $products = $products->orderBy('price', 'ASC');

            }else{
                $products = $products->orderBy('price','DESC');
            }
        }else{
            $products = $products->orderBy('id','DESC');
        }
        
        $products = $products->paginate(6)->withQueryString();
        
       // $products = $products->orderBy('id', 'DESC');
        //$products = $products->get();
        // $products = $products->orderBy('id', 'DESC')->get();
        $data['categories'] = $categories;
        $data['brands'] = $brands;
        $data['products'] = $products;
        $data['categorySelected'] = $categorySelected;
        $data['subCategorySelected'] = $subCategorySelected;
        $data['brandsArray'] = $brandsArray;
        $data['priceMax'] = (intval($request->get('price_max')) == 0) ? 5000000 : $request->get('price_max');
        $data['priceMin'] = intval($request->get('priceMin'));
        $data['sort'] = $request->get('sort');
       
      


        return view('front.shop',$data);

    }

    public function product($slug){
        $product = Product::where('slug',$slug)->with('product_images')->first();
        if($product  == null){
            abort(404);
        }


        //fetch related product
        $relatedProducts = [];
        if ($product->related_products != '') {
            $productArray = explode(',', $product->related_products);
            $relatedProducts = Product::whereIn('id', $productArray)->where('status',1)->get();
        }  

        $data['product'] = $product;
        $data['relatedProducts'] = $relatedProducts;


        return view('front.product',$data);

    }
}
