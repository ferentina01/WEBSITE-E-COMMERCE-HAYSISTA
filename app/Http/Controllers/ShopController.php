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
            $products = $products->whereBetween ('price', [$request->get('price_min'),]);

        }
        
        $products = $products->orderBy('id', 'DESC');
        $products = $products->get();
        // $products = $products->orderBy('id', 'DESC')->get();
        $data['categories'] = $categories;
        $data['brands'] = $brands;
        $data['products'] = $products;
        $data['categorySelected'] = $categorySelected;
        $data['subCategorySelected'] = $subCategorySelected;
        $data['brandsArray'] = $brandsArray;
      


        return view('front.shop',$data);

    }
}
