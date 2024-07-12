<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Support\Facades\File;







class CategoryController extends Controller
{
    //
    public function index(Request $request)
    {
        // $categories = Category::latest();

        // if (!empty($request->get('keyword'))) {
        //     $categories = $categories->where('name', 'like', '%' . $request->get('keyword') . '%');
        // }

        // Ambil query builder dari model Category, diurutkan berdasarkan ID secara ascending
        $categories = Category::orderBy('id', 'asc');

        // Jika ada keyword pencarian yang diberikan melalui request, tambahkan kondisi pencarian
        if (!empty($request->get('keyword'))) {
            $keyword = '%' . $request->get('keyword') . '%';
            $categories->where('name', 'like', $keyword);
        }

        $categories = $categories->paginate(10);
        return view('admin.category.list', compact('categories'));
    }

    public function create()
    {

        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories',

        ]);
        if ($validator->passes()) {
            $category = new Category();
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;
            $category->save();


            //save image here
            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);

                $newImageName = $category->id . '.' . $ext;            
                $sourcePath = public_path('/temp/thumb/') . '/' . $tempImage->name;
                $destPath = public_path('/uploads/category') . '/' . $newImageName;
                File::move($sourcePath, $destPath);
                    




                $category->image = $newImageName;
                $category->save();
            }


            session()->flash('success', 'Kategori Baru Berhasil dibuat');

            return response()->json([
                'status' => true,
                'massage' => 'Kategori Baru Berhasil dibuat'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($categoryId, Request $request)
    {
        $category = Category::find($categoryId);
        if (empty($category)) {
            return redirect()->route('categories.index');
        }
        return view('admin.category.edit', compact('category'));
    }

    public function update($categoryId, Request $request)

    {
        $category = Category::find($categoryId);
        if (empty($category)) {
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Category not found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,' . $category->id . ',id',
        ]);

        if ($validator->passes()) {

            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;

            $category->save();

            $oldImage = $category->image;

            //save image here
            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);

                $newImageName = $category->id . '-' . time() . '.' . $ext;
                $sourcePath = public_path('/temp/thumb/') . '/' . $tempImage->name;
                $destPath = public_path('/uploads/category') . '/' . $newImageName;
                File::move($sourcePath, $destPath);
                 
                $category->image = $newImageName;
                $category->save();

                //delete old Image here
                File::delete(public_path() . '/uploads/category/' . $oldImage);
            }

            session()->flash('success', 'Category Updated successfully');

            return response()->json([
                'status' => true,
                'massage' => 'Category Updated Succesfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }




    public function destroy($categoryId, Request $request)
    {
        $category = Category::find($categoryId);
        if (empty($category)) {
            return response()->json([
                'status' => false,
                'message' => 'Category not found'
            ]);
        }

        File::delete(public_path() . '/uploads/category/' . $category->image);

        $category->delete();

        return response()->json([
            'status' => true,
            'message' => 'Category deleted successfully'
        ]);
    }
}
