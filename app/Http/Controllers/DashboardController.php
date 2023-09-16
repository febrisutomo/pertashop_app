<?php

namespace App\Http\Controllers;

use App\Models\DailyReport;
use App\Models\InvestorProfit;
use App\Models\RekapModal;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $currentTime = Carbon::now();

        // Mendapatkan waktu saat ini dalam format 24 jam
        $currentHour = $currentTime->format('H');

        $sapaan = '';

        if ($currentHour >= 5 && $currentHour < 12) {
            $sapaan = 'Selamat Pagi';
        } elseif ($currentHour >= 12 && $currentHour < 17) {
            $sapaan = 'Selamat Siang';
        } elseif ($currentHour >= 17 && $currentHour < 20) {
            $sapaan = 'Selamat Sore';
        } else {
            $sapaan = 'Selamat Malam';
        }

        if (collect(['super-admin', 'admin', 'investor'])->contains(Auth::user()->role)) {

            if (Auth::user()->role == 'admin') {
                $shop_id = Auth::user()->shop_id;
                $stocks = $this->getStocks($shop_id);
            } else {
                $shop_id = $request->input('shop_id', 1);
                $stocks = $this->getStocks();
            }

            $year_month = $request->input('year_month', Carbon::now()->format('Y-m'));
            list($year, $month) = explode('-', $year_month);

            $shops = Shop::all();

            $sales = $this->getSales($shop_id, $year_month);
            $summary = LabaKotorController::getLabaKotorFinal($shop_id, Carbon::now()->format('Y-m'));

            if (auth()->user()->role != 'investor') {
                return view('dashboard.index', compact('shops', 'sales', 'stocks', 'summary', 'sapaan'));
            } else {
                $shops = Auth::user()->investments;

                $posisi_modal = RekapModal::where('shop_id', $shop_id)->latest()->first()?->modal_akhir;

                $total_profit_pertashop = InvestorProfit::whereRelation('profitSharing', 'shop_id', $shop_id)->sum('nilai_profit');

                $total_profit_anda = InvestorProfit::whereRelation('investorShop', 'user_id', Auth::user()->id)->sum('nilai_profit');

                return view('dashboard.index-investor', compact('shops', 'sapaan', 'sales', 'summary', 'total_profit_anda', 'total_profit_pertashop', 'posisi_modal'));
            }
        } else {

            $operator_id = Auth::user()->id;
            $shop_id = Auth::user()->shop_id;

            $today_report = DailyReport::where('shop_id', $shop_id)->whereDate('created_at', Carbon::now()->format('Y-m-d'))->get();
            $latest_report =  DailyReport::where('shop_id', $shop_id)->latest()->first();
            $stok_akhir = $latest_report ? $latest_report->stok_akhir_aktual : Shop::find($shop_id)->stok_awal;
            $totalisator_akhir = $latest_report ? $latest_report->totalisator_akhir : Shop::find($shop_id)->totalisator_awal;
            $volume_penjualan = $today_report->sum('volume_penjualan');
            $rupiah_penjualan = $today_report->sum('rupiah_penjualan');
            $tabungan = DailyReport::where('operator_id', $operator_id)->get()->sum('selisih_setoran');

            return view('dashboard.index-operator', compact('sapaan', 'tabungan', 'stok_akhir', 'totalisator_akhir', 'volume_penjualan', 'rupiah_penjualan'));
        }
    }


    protected function getStocks($shop_id = null)
    {

        if ($shop_id) {
            $shops = Shop::where('id', $shop_id)->get();
        } else {
            $shops = Shop::all();
        }

        $data = [
            'labels' => [],
            'datasets' => [
                [
                    'label' => 'Stok',
                    'data' => [],
                    'backgroundColor' => [], // Warna latar belakang untuk setiap bar
                ],
                [
                    'label' => 'Kapasitas',
                    'data' => [],
                    'backgroundColor' => [], // Warna latar belakang untuk setiap bar
                ],
            ]
        ];
        foreach ($shops as $shop) {
            $latest_report = DailyReport::where('shop_id', $shop->id)->latest()->first();
            $data['labels'][] = $shop->nama;
            $stok_akhir = $latest_report ? $latest_report->stok_akhir_aktual : Shop::find($shop->id)->kapasitas;
            $data['datasets'][0]['data'][] = $stok_akhir;
            $data['datasets'][1]['data'][] = Shop::find($shop->id)->kapasitas;
            // Tentukan warna berdasarkan kondisi stok kurang dari 1500
            if ($stok_akhir < 1500) {
                $data['datasets'][0]['backgroundColor'][] = '#dc3545'; // Warna hijau jika stok cukup
            } else {
                $data['datasets'][0]['backgroundColor'][] = '#28a745'; // Warna merah jika stok kurang dari 1500
            }
            $data['datasets'][1]['backgroundColor'][] = 'lightgray';
        }

        return $data;
    }


    protected function getSales($shop_id = 1, $year_month)
    {
        $shop = Shop::find($shop_id);

        list($year, $month) = explode('-', $year_month);

        $reports = DailyReport::where('shop_id', $shop->id)->whereYear('created_at', $year)->whereMonth('created_at', $month)->get();

        $startDate = Carbon::createFromDate($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();
        // if ($year_month == Carbon::now()->format('Y-m')) {
        //     $endDate = Carbon::now();
        // }
        $currentDate = $startDate;

        $dailyReports = $reports->groupBy(function ($item) {
            return $item->created_at->format('d M');
        });

        $sales = [];

        while ($currentDate <= $endDate) {
            $formattedDate = $currentDate->format('d M');
            if (isset($dailyReports[$formattedDate])) {
                $sales[$formattedDate] = $dailyReports[$formattedDate]->sum('volume_penjualan');
            } else {
                $sales[$formattedDate] = null;
            }
            $currentDate->addDay();
        }

        $labels = array_keys($sales);
        $data = array_values($sales);

        return compact('labels', 'data');
    }
}
