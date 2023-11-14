<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Price;
use App\Models\Purchase;
use App\Models\LabaKotor;
use App\Models\LabaBersih;
use App\Models\RekapModal;
use App\Models\DailyReport;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RekapModalController extends Controller
{

    public function index(Request $request)
    {

        if (Auth::user()->role == 'admin') {
            $shop_id = Auth::user()->shop_id;
        } else {
            $shop_id = $request->input('shop_id', 1);
        }


        $modals = RekapModal::where('shop_id', $shop_id)->get();

        // dd($modals);

        $shops = Shop::all();
        if (Auth::user()->role == 'investor') {
            $shops = Auth::user()->investments;
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

        $labaBersih = LabaBersih::where('shop_id', $shop_id)->whereYear('created_at', $year)->whereMonth('created_at', $month)->first();

        if ($labaBersih == null) {
            return redirect()->back()->with('error', 'Laba bersih belum dibuat');
        }

        RekapModal::create([
            'shop_id' => $shop_id,
            'created_at' => Carbon::createFromFormat('Y-m', $year_month)->endOfMonth(),
            'rugi' => $labaBersih->laba_bersih < 0 ? $labaBersih->laba_bersih : 0,
            'alokasi_keuntungan' => $labaBersih->alokasi_modal,
        ]);


        return redirect()->route('rekap-modal.edit', [$shop_id, $year_month])->with('success', 'Rekap modal berhasil dibuat');
    }

    public function edit(string $shop_id, string $year_month)
    {
        $shop = Shop::find($shop_id);

        list($year, $month) = explode('-', $year_month);

        $modal = RekapModal::where('shop_id', $shop_id)->whereYear('created_at', $year)->whereMonth('created_at', $month)->first();

        $piutang = $modal->piutang;

        if($shop->id == 5){
            $piutang = DailyReport::where('shop_id', $shop_id)->whereYear('created_at', $year)->whereMonth('created_at', $month)->sum('setor_transfer');
        }
        
        if ($modal == null) {
            abort(404);
        }

        $reports = DailyReport::where('shop_id', $shop_id)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->oldest()
            ->get();



        $harga_beli = $reports->last()?->price?->harga_beli ?? Price::where('created_at', '<=', $modal->created_at)->latest()->first()->harga_beli ?? 0;

        $labaKotor = LabaKotor::where('shop_id', $shop_id)->whereYear('created_at', $year)->whereMonth('created_at', $month)->first();

        $sisa_do = $labaKotor->sisa_do ?? 0;
        $rupiah_sisa_do = $sisa_do * $harga_beli;

        $belum_disetorkan = $reports->last()?->tabungan ?? 0;

        $sisa_stok = $reports->last()?->stok_akhir_aktual ?? 0;
        $rupiah_sisa_stok = $sisa_stok * $harga_beli;

        $uang_di_bank = $modal->modal_awal - $rupiah_sisa_do - $modal->kas_kecil + $belum_disetorkan - $modal->piutang - $rupiah_sisa_stok;

        return view('rekap-modal.edit', compact('shop', 'modal', 'belum_disetorkan', 'sisa_do', 'rupiah_sisa_do', 'harga_beli', 'uang_di_bank', 'sisa_stok', 'rupiah_sisa_stok', 'piutang'));
    }


    public function update(Request $request, RekapModal $rekapModal)
    {
        $customMessages = [
            'required' => ':attribute wajib diisi.',
        ];

        $validatedData = $request->validate([
            'kas_kecil' => 'required|numeric',
            'piutang' => 'required|numeric',
            'bunga_bank' => 'required|numeric',
            'pajak_bank' => 'required|numeric',
        ], $customMessages);

        $rekapModal->update($validatedData);

        return redirect()->back()->with('success', 'Rekap modal berhasil diubah');
    }

    public function destroy(RekapModal $rekapModal)
    {
        $rekapModal->delete();

        //return json
        return response()->json([
            'success' => true,
            'message' => 'Rekap modal berhasil dihapus',
        ]);
    }
}
