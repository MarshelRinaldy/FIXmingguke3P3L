<?php

namespace App\Http\Controllers;

use App\Models\Dukpro;
use Illuminate\Http\Request;
use App\Models\Hampers;

class DashboardController extends Controller
{
    public function index(){
        
        $data['produk'] = Dukpro::whereIn('status', ['Available', 'Preorder'])->get();
        $data['hampers'] = Hampers::all();
        return view('dashboard',$data);
    }


     public function home(){
        
        $data['produk'] = Dukpro::whereIn('status', ['Available', 'Preorder'])->get();

        return view('dashboardCustomer',$data);
    }
}
