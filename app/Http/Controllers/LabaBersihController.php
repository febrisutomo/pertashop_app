<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Spending;
use App\Models\LabaBersih;
use App\Models\RekapModal;
use App\Models\DailyReport;
use Illuminate\Http\Request;
use App\Models\ProfitSharing;
use Illuminate\Support\Carbon;
use App\Models\SpendingCategory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LabaKotorController;

class LabaBersihController extends Controller
{

    public function index(Request $request)
    {

        if (Auth::user()->role == 'admin') {
            $shop_id = Auth::user()->shop_id;
        } else {
            $shop_id = $request->input('shop_id', 1);
        }

        $shops = Shop::all();
        if (Auth::user()->role == 'investor') {
            $shops = Auth::user()->investments;
        }

        $shop = Shop::find($shop_id);

        $labaBersih = LabaBersih::where('shop_id', $shop_id)->latest()->get();

        return view('laba_bersih.index', compact('shops', 'labaBersih', 'shop'));
    }

    public function store(Request $request)
    {
        $shop_id = $request->input('shop_id', 1);

        $year_month = $request->input('year_month', Carbon::now()->format('Y-m'));

        list($year, $month) = explode('-', $year_month);

        //check if laba_bersih already exists
        $laba_bersih_exist = LabaBersih::where('shop_id', $shop_id)->whereYear('created_at', $year)->whereMonth('created_at', $month)->first();

        if ($laba_bersih_exist) {
            return redirect()->back()->with('error', 'Laporan Laba Bersih sudah ada');
        }

        $reports = DailyReport::where('shop_id', $shop_id)->whereYear('created_at', $year)->whereMonth('created_at', $month)->get();

        if ($reports->isEmpty()) {
            return redirect()->back()->with('error', 'Belum ada penjualan di bulan ini');
        }

        $summary = LabaKotorController::getLabaKotorFinal($shop_id, $year_month);

        $laba_kotor = $summary['laba_kotor'];

        $total_biaya = $reports->sum('pengeluaran');

        try {
            DB::beginTransaction();


            $labaBersih = LabaBersih::create([
                'shop_id' => $shop_id,
                'created_at' => Carbon::createFromFormat('Y-m', $year_month)->endOfMonth(),
                'laba_kotor' => $laba_kotor,
                'total_biaya' => $total_biaya,
            ]);

            //create profit sharing
            $profitSharing = ProfitSharing::create([
                'shop_id' => $shop_id,
                'created_at' => $labaBersih->created_at,
                'nilai_profit_sharing' => $labaBersih->laba_bersih,
                'alokasi_modal' => $labaBersih->alokasi_modal,
            ]);

            $shop = Shop::find($shop_id);

            $investor_profits = $shop->investors->map(function ($investor) use ($profitSharing) {
                return [
                    'investor_shop_id' => $investor->pivot->id,
                    'nilai_profit' => $investor->pivot->persentase / 100 * ($profitSharing->nilai_profit_sharing - $profitSharing->alokasi_modal),
                ];
            });

            $profitSharing->investorProfits()->createMany($investor_profits);

            DB::commit();

            return redirect()->route('laba-bersih.edit', [$shop_id, $year_month])->with('success', 'Laporan Laba Bersih berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Transaction failed: ' . $e->getMessage());
        }
    }

    public function edit(string $shop_id, string $year_month)
    {
        list($year, $month) = explode('-', $year_month);

        $summary = LabaKotorController::getLabaKotorFinal($shop_id, $year_month);
        $reports = DailyReport::where('shop_id', $shop_id)->whereYear('created_at', $year)->whereMonth('created_at', $month)->get();

        //update laba bersih if reports updated
        $report = LabaBersih::where('shop_id', $shop_id)->whereYear('created_at', $year)->whereMonth('created_at', $month)->first();

        try {
            DB::beginTransaction();

            $report->update([
                'laba_kotor' => $summary['laba_kotor'],
                'total_biaya' => $reports->sum('pengeluaran') + $report->gaji_operator + $report->gaji_admin,
            ]);

            $report = LabaBersih::where('shop_id', $shop_id)->whereYear('created_at', $year)->whereMonth('created_at', $month)->first();

            $modal = RekapModal::where('shop_id', $shop_id)->whereYear('created_at', $year)->whereMonth('created_at', $month)->first();

            if ($modal) {
                $modal->update([
                    'rugi' => $report->laba_bersih < 0 ? $report->laba_bersih : 0,
                    'alokasi_keuntungan' => $report->alokasi_modal,
                ]);
            }

            //update profit sharing if laba bersih updated
            $profitSharing = ProfitSharing::where('shop_id', $report->shop_id)->whereYear('created_at', $report->created_at->format('Y'))->whereMonth('created_at', $report->created_at->format('m'))->first();

            if ($profitSharing) {
                $profitSharing->update([
                    'alokasi_modal' => $report->alokasi_modal,
                    'nilai_profit_sharing' => $report->laba_bersih,
                ]);

                $shop =  Shop::with(['investors'])->find($report->shop_id);
                $investor_profits = $shop->investors->map(function ($investor) use ($profitSharing) {
                    return [
                        'investor_shop_id' => $investor->pivot->id,
                        'nilai_profit' => $investor->pivot->persentase / 100 * ($profitSharing->nilai_profit_sharing - $profitSharing->alokasi_modal),
                    ];
                });

                $profitSharing->investorProfits()->delete();

                $profitSharing->investorProfits()->createMany($investor_profits);
            }

            $shop =  Shop::with(['investors'])->find($shop_id);

            $spendings =  Spending::whereHas('dailyReport', function ($query) use ($month, $year, $shop_id) {
                $query->where('shop_id', $shop_id)
                    ->whereMonth('created_at', $month)
                    ->whereYear('created_at', $year);
            })->orderBy('category_id')
                ->get()->groupBy('category_id');

            //spendings to array
            $spendings = $spendings->map(function ($value, $key) {
                $category = SpendingCategory::find($key)->nama;
                return ['pengeluaran' => $category, 'jumlah' => $value->sum('jumlah')];
            });

            DB::commit();

            return view('laba_bersih.edit', compact('report', 'shop', 'spendings'));
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Transaction failed: ' . $e->getMessage());
        }
    }

    public function update(Request $request, LabaBersih $labaBersih)
    {

        $customMessages = [
            'required' => ':attribute wajib diisi.',
        ];

        $validatedData = $request->validate([
            'gaji_operator' => 'required|numeric',
            'gaji_admin' => 'required|numeric',
            'persentase_alokasi_modal' => 'required|numeric',
        ], $customMessages);


        try {
            DB::beginTransaction();

            $labaBersih->update($validatedData);

            $labaBersih = LabaBersih::where('shop_id', $labaBersih->shop_id)->whereYear('created_at', $labaBersih->created_at->format('Y'))->whereMonth('created_at', $labaBersih->created_at->format('m'))->first();

            $modal = RekapModal::where('shop_id', $labaBersih->shop_id)->whereYear('created_at', $labaBersih->shop_id)->whereMonth('created_at', $labaBersih->created_at->format('Y'))->first();

            if ($modal) {
                $modal->update([
                    'rugi' => $labaBersih->laba_bersih < 0 ? $labaBersih->laba_bersih : 0,
                    'alokasi_keuntungan' => $labaBersih->alokasi_modal,
                ]);
            }

            $profitSharing = ProfitSharing::where('shop_id', $labaBersih->shop_id)->whereYear('created_at', $labaBersih->created_at->format('Y'))->whereMonth('created_at', $labaBersih->created_at->format('m'))->first();

            if ($profitSharing) {
                $profitSharing->update([
                    'alokasi_modal' => $labaBersih->alokasi_modal,
                    'nilai_profit_sharing' => $labaBersih->laba_bersih,
                ]);

                $shop =  Shop::with(['investors'])->find($labaBersih->shop_id);
                $investor_profits = $shop->investors->map(function ($investor) use ($profitSharing) {
                    return [
                        'investor_shop_id' => $investor->pivot->id,
                        'nilai_profit' => $investor->pivot->persentase / 100 * ($profitSharing->nilai_profit_sharing - $profitSharing->alokasi_modal),
                    ];
                });

                $profitSharing->investorProfits()->delete();

                $profitSharing->investorProfits()->createMany($investor_profits);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Laporan Laba Bersih berhasil diubah');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Transaction failed: ' . $e->getMessage());
        }
    }

    public function destroy(LabaBersih $labaBersih)
    {
        $labaBersih->delete();

        //delete profit sharing

        $profitSharing = ProfitSharing::where('shop_id', $labaBersih->shop_id)->whereYear('created_at', $labaBersih->created_at->format('Y'))->whereMonth('created_at', $labaBersih->created_at->format('m'))->first();

        if ($profitSharing) {
            $profitSharing->delete();
        }

        $rekapModal = RekapModal::where('shop_id', $labaBersih->shop_id)->whereYear('created_at', $labaBersih->created_at->format('Y'))->whereMonth('created_at', $labaBersih->created_at->format('m'))->first();

        if ($rekapModal) {
            $rekapModal->delete();
        }

        //return json
        return response()->json([
            'success' => true,
            'message' => 'Laporan Laba Bersih berhasil dihapus',
        ]);
    }
}
