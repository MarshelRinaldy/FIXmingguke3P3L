<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Models\Dukpro;
use App\Models\Hampers;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardCustomerController extends Controller
{
    public function index(Request $request)
    {

        $today = Carbon::today();
        $date = $request->input('date-picker') ?? $today->toDateString();

        if ($date) {
            $selectedDate = Carbon::parse($date);
            $produk = Dukpro::whereIn('status', ['Available', 'Preorder'])
                // ->where('tanggal_kadaluarsa', '>=', $today)
                // ->where('tanggal_kadaluarsa', '<=', $selectedDate)
                ->get();
        } else {
            $produk = Dukpro::whereIn('status', ['Available', 'Preorder'])
                ->where('tanggal_kadaluarsa', '>=', $today)
                ->get();
        }

        // Menambahkan data hampers
        $hampers = Hampers::all();

        return view('customer.dashboard.dashboardCustomer', compact('produk', 'hampers', 'date', 'today'));
    }
    public function filter(Request $request)
    {
        $date = $request->input('date');
        $data['produk'] = Dukpro::whereIn('status', ['Available', 'Preorder'])
            ->where('tanggal_kadaluarsa', '>=', $date)
            ->get();
        return response()->json($data);
    }

}
