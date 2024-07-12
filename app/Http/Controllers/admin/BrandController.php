<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Brand;


class BrandController extends Controller
{

    public function index(Request $request)
    {
        // $brands = Brand::latest('id');

        // if ($request->get('keyword')) {
        //     $brands = $brands->where('name', 'like', '%' . $request->keyword . '%');
        // }

        // Ambil query builder dari model Brand, diurutkan berdasarkan ID secara descending
        $brands = Brand::orderBy('id', 'asc');

        // Jika ada keyword pencarian yang diberikan melalui request, tambahkan kondisi pencarian
        if ($request->get('keyword')) {
            $keyword = '%' . $request->keyword . '%';
            $brands->where('name', 'like', $keyword);
        }

        $brands = $brands->paginate(10);

        return view('admin.brands.list', compact('brands'));
    }


    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:brands',
        ]);

        if ($validator->passes()) {
            //Simpan data brand ke database
            $brand = new Brand();
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();

            session()->flash('success', 'Merek Berhasil Ditambahkan');

            return response()->json([
                'status' => true,
                'message' => 'Merek baru berhasil ditambahkan!'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($id, Request $request)
    {
        $brand = Brand::find($id);

        if (empty($brand)) {
            session()->flash('error', 'Record not Found');
            return redirect()->route('brands.index');
        }

        $data['brand'] = $brand;
        return view('admin.brands.edit', $data);
    }


    public function update($id, Request $request)
    {

        $brand = Brand::find($id);

        if (empty($brand)) {
            session()->flash('error', 'Record not Found');

            return response()->json([
                'status' => false,
                'notFound' => true
            ]);

            // return redirect()->route('brands.index');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,'.$brand->id.',id',
        ]);

        if ($validator->passes()) {
            //Simpan data brand ke database
            
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();

            session()->flash('success', 'Merek berhasil diperbarui!');
            return response()->json([
                'status' => true,
                'message' => 'Merek Berhasil dibuat!'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    

    public function destroy($id, Request $request)
    {
        $brand = Brand::find($id);
        if (empty($brand)) {
            session()->flash('error', 'Record not found');
            return response([
                'status' => false,
                'notFound' => true,
            ]);
            //return redirect()->route('sub-categories.index');
        }

        $brand->delete();
        session()->flash('success', 'Brand deleted successfully');

        return response([
            'status' => true,
            'message' => 'Brand deleted successfully'
        ]);
    }
       
    
}
