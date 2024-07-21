<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Product;
use App\Models\TempImage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;


class HomeController extends Controller
{
    //
    public function index()
    {

        
        $totalProducts = Product::count();
        $totalOrders = Order::where('status','!=','canceled')->count();
        $totalRevenue = Order::where('status','!=','canceled')->sum('grand_total');
        $totalCustomers = User::where('role',1)->count();

        //Revenue in month

        
        $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d'); 
        $currentDate = Carbon::now()->format('Y-m-d');

        $revenueThisMonth = Order::where('status', '!=', 'cancelled')
                        ->whereDate('created_at', '>=', $startOfMonth)
                        ->whereDate('created_at', '<=', $currentDate)
                        ->sum('grand_total');

        //delete temp images here

        // $dayBeforeToday = Carbon::now()->subDays(1)->format('Y-m-d');
        // $tempImages = TempImage::where('created_at', '<=', $dayBeforeToday)->get();
        // foreach ($tempImages as $tempImage) {
        //     $path = public_path('/temp/' . $tempImage->name);
        //     $thumbPath = public_path('/temp/thumb/' . $tempImage->name);
        //     // Delete Main Image
        //     if (File::exists($path)) {
        //         File::delete($path);
        //     }
            
        //     // Delete Thumb Image
        //     if (File::exists($thumbPath)) {
        //         File::delete($thumbPath);

        //     }
        //     TempImage::where('id',$tempImage->id)->delete();
        // }    



        
        // Last month revenue
        $lastMonthStartDate = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d'); 
        $lastMonthEndDate = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
        $lastMonthName = Carbon::now()->subMonth()->startOfMonth()->format('M'); 

        $revenueLastMonth = Order::where('status', '!=', 'cancelled')
                        ->whereDate('created_at', '>=', $lastMonthStartDate)
                        ->whereDate('created_at', '<=', $lastMonthEndDate)
                        ->sum('grand_total');

        
        // Last 30 days sale
        $lastThirtyDayStartDate = Carbon::now()->subDays (30)->format('Y-m-d');
        $revenueLastThirtyDays = Order::where('status', '!=', 'cancelled')
        ->whereDate('created_at', '>=', $lastThirtyDayStartDate) 
        ->whereDate('created_at', '<=', $currentDate)
        ->sum('grand_total');                




        return view('admin.dashboard',[
            'totalOrders' => $totalOrders,
            'totalProducts' => $totalProducts,
            'totalCustomers' => $totalCustomers,
            'totalRevenue' => $totalRevenue,
            'revenueThisMonth' => $revenueThisMonth,
            'revenueLastMonth' => $revenueLastMonth,
            'revenueLastThirtyDays' => $revenueLastThirtyDays,
            'lastMonthName' => $lastMonthName,

        ]);
        //$admin = Auth::guard('admin')->user();
        //echo 'we' . $admin->name . '<a href="' . route('admin.logout') . '">Logout</a>';
        
    }


    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
