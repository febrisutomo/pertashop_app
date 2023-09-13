<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Purchase;
use App\Models\RekapModal;
use App\Models\DailyReport;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Price;
use Illuminate\Support\Facades\Auth;

class RekapModalController extends Controller
{

    public function index(Request $request)
    {

        $shop_id = $request->input('shop_id', 1);


        $modals = RekapModal::where('shop_id', $shop_id)->get();

        // dd($modals);


        $shops = Shop::all();
        if (Auth::user()->role == 'investor') {
            $shops = Auth::user()->investor->shops;
        }

        $shop = Shop::find($shop_id);



        return view('rekap-modal.index', compact('shops', 'modals', 'shop'));
    }

    public function store(Request $request)
    {

        $shop_id = $request->input('shop_id', 1);

        $year_month = $request->input('year_month', Carbon::now()->format('Y-m'));

        list($year, $month) = explode('-', $year_month);

        //check if modal already exists
        $modal_exist = RekapModal::where('shop_id', $shop_id)->whereYear('created_at', $year)->whereMonth('created_at', $month)->first();

        if ($modal_exist) {
            return redirect()->back()->with('error', 'Rekap modal sudah ada');
        }

        $laba_bersih = LabaBersihController::getLabaBersih($shop_id, $year_month);

        RekapModal::create([
            'shop_id' => $shop_id,
            'created_at' => Carbon::createFromFormat('Y-m', $year_month)->endOfMonth(),
            'rugi' => $laba_bersih['laba_bersih'] < 0 ? $laba_bersih['laba_bersih'] : 0,
            'alokasi_keuntungan' => $laba_bersih['alokasi_modal'],
        ]);


        return redirect()->route('rekap-modal.edit', [$shop_id, $year_month])->with('success', 'Rekap modal berhasil dibuat');
    }

    public function edit(string $shop_id, string $year_month)
    {
        $shop = Shop::find($shop_id);
        $date = Carbon::createFromFormat('Y-m', $year_month);

        $modal = RekapModal::where('shop_id', $shop_id)->whereYear('created_at', $date->year)->whereMonth('created_at', $date->month)->first();

        $reports = DailyReport::where('shop_id', $shop_id)
            ->whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)
            ->get();

        $harga_beli = $reports->last()?->price?->harga_beli ?? Price::where('created_at', '<=', $date)->latest()->first()->harga_beli ?? 0;

        $purchase = Purchase::where('shop_id', $shop_id)->whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)->latest()->first();

        $sisa_do = $purchase == null || $purchase->incoming ? 0 : $purchase->volume;

        $rupiah_sisa_do = $sisa_do * $harga_beli;

        $report_by_operator = $reports->groupBy('operator_id');

        //get latest daily report each operator
        $report_by_operator = $report_by_operator->map(function ($item) {
            return $item->last();
        });

        $belum_disetorkan = $report_by_operator->sum('belum_disetorkan');


        $laba_bersih = LabaBersihController::getLabaBersih($shop_id, $year_month);
        $alokasi_keuntungan = $laba_bersih['alokasi_modal'];
        $profit_sharing = $laba_bersih['laba_bersih_dibagi'];

        return view('rekap-modal.edit', compact('shop', 'date', 'modal', 'alokasi_keuntungan', 'profit_sharing', 'belum_disetorkan', 'sisa_do', 'rupiah_sisa_do', 'harga_beli'));
    }

    public function destroy(string $id)
    {

        $modal = RekapModal::find($id);

        $modal->delete();

        //return json
        return response()->json([
            'success' => true,
            'message' => 'Rekap modal berhasil dihapus',
        ]);
    }
}
