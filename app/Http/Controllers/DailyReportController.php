<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Price;
use App\Models\DailyReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DailyReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $harga = Price::latest()->first()->harga_jual;
        #get shop id for admin or operator login
        if (Auth::user()->role == 'admin') {
            $shop_id = Auth::user()->admin->shop_id;
        } elseif (Auth::user()->role == 'operator') {
            $shop_id = Auth::user()->operator->shop_id;
        } else {
            $shop_id = 1;
        }
        $shop = Shop::find($shop_id);
        $totalisator_awal = ReportController::calcLabaKotor($shop_id)->last() ? ReportController::calcLabaKotor($shop_id)->last()['totalisator_akhir'] : Shop::find($shop_id)->totalisator_awal;
        return view('daily-report.create', compact('harga', 'shop', 'totalisator_awal'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(DailyReport $dailyReport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DailyReport $dailyReport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DailyReport $dailyReport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DailyReport $dailyReport)
    {
        //
    }
}
