<?php

namespace App\Http\Controllers;

use App\Models\DailyReport;
use App\Models\Sale;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            if (Auth::user()->role == 'admin') {
                $shop_id = Auth::user()->admin->shop_id;
            } elseif (Auth::user()->role == 'operator') {
                $shop_id = Auth::user()->operator->shop_id;
            } else {
                $shop_id = $request->input('shop_id');
            }

            $time_filter = $request->input('filter');

            $sales = $this->getSales($shop_id, $time_filter);

            $stocks = $this->getStocks($shop_id);

            $summaries = $this->getSummaries($shop_id);

            return response()->json([
                'stocks' => $stocks,
                'sales' => $sales,
                'summaries' => $summaries
            ]);
        }
        $reports = ReportController::calcLabaKotor(1);

        $data = [
            'reports' => $reports,
            'shops' => Shop::all()
        ];

        if (Auth::user()->role === 'operator') {
            $operator_id = Auth::user()->operator->id;
            $latest_report = DailyReport::where('operator_id', $operator_id)->latest()->first();
            $stok_akhir = $latest_report ? $latest_report->stok_akhir_aktual : 0;
            $totalisator_akhir = $latest_report ? $latest_report->totalisator_akhir : 0;
            $belum_disetorkan = $latest_report ? $latest_report->belum_disetorkan : 0;
            $volume_penjualan = $latest_report ? $latest_report->volume_penjualan : 0;
            $rupiah_penjualan = $latest_report ? $latest_report->rupiah_penjualan : 0;

            return view('dashboard.operator', compact('belum_disetorkan', 'stok_akhir', 'totalisator_akhir', 'volume_penjualan', 'rupiah_penjualan', 'data'));
        }
        return view('dashboard.index', $data);
    }

    protected function getSummaries($shop_id)
    {
        $shops = Shop::all();

        if ($shop_id) {
            $shops = Shop::where('id', $shop_id)->get();
        }

        $data = [];

        foreach ($shops as $index => $shop) {
            $data[$index] = ReportController::getSummary($shop->id);
            $data[$index]['shop'] = $shop;
        }

        return $data;
    }

    protected function getStocks($shop_id)
    {
        $shops = Shop::all();

        if ($shop_id) {
            $shops = Shop::where('id', $shop_id)->get();
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
            $data['labels'][] = $shop->nama;
            $stok_akhir = DailyReport::where('shop_id', $shop->id)->latest()->first() ? DailyReport::where('shop_id', $shop->id)->latest()->latest()->first()->stok_akhir_aktual : Shop::find($shop->id)->kapasitas;
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


    protected function getSales($shop_id, $time_filter)
    {
        $shops = Shop::all();

        if ($shop_id) {
            $shops = Shop::where('id', $shop_id)->get();
        }

        $labels = [];
        $datasets = [];
        foreach ($shops as $index => $shop) {

            $sales = DailyReport::where('shop_id', $shop->id)->whereMonth('created_at', Carbon::now()->month)->oldest();
            if ($time_filter === 'week') {
                $startDateOfMonth = Carbon::now()->startOfMonth();
                $sales = $sales->where('created_at', '>=', $startDateOfMonth)->get()->groupBy(function ($item) use ($startDateOfMonth) {
                    $daysDiff = $item->created_at->diffInDays($startDateOfMonth);
                    $weekOfMonth = ceil(($daysDiff + $startDateOfMonth->dayOfWeek) / 7);
                    // $monthName = $startDateOfMonth->format('F');
                    return "Minggu ke-" . $weekOfMonth;
                });

                // Mengisi tanggal-tanggal tanpa penjualan dengan nilai nol
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $currentDate = $startDate;
                while ($currentDate <= $endDate) {
                    $daysDiff = $currentDate->diffInDays($startDateOfMonth);
                    $weekOfMonth = ceil(($daysDiff + $startDateOfMonth->dayOfWeek) / 7);
                    $formattedDate = "Minggu ke-" . $weekOfMonth;
                    if (!isset($sales[$formattedDate])) {
                        $sales[$formattedDate] = [];
                    }
                    $currentDate->addWeek();
                }

                $sortedSales = $sales->toArray();
                ksort($sortedSales);
            } elseif ($time_filter === 'month') {
                $startMonth = Carbon::now()->startOfYear();
                $endMonth = Carbon::now()->endOfYear();
                $currentMonth = $startMonth;

                $sales = $sales->where('created_at', '>=', $startMonth)->get()->groupBy(function ($item) {
                    return $item->created_at->format('M Y');
                });

                while ($currentMonth <= $endMonth) {
                    $formattedMonth = $currentMonth->format('M Y');
                    if (!isset($sales[$formattedMonth])) {
                        $sales[$formattedMonth] = [];
                    }
                    $currentMonth->addMonth();
                }

                $sortedSales = $sales->toArray();
                // Sort the array using the custom comparison function
                uksort($sortedSales, function ($a, $b) {
                    $dateA = Carbon::createFromFormat('M Y', $a);
                    $dateB = Carbon::createFromFormat('M Y', $b);

                    return $dateA < $dateB ? -1 : ($dateA > $dateB ? 1 : 0);
                });
            } else {
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $currentDate = $startDate;

                $sales = $sales->where('created_at', '>=', $startDate)->get()->groupBy(function ($item) {
                    return $item->created_at->format('d M');
                });

                // Mengisi tanggal-tanggal tanpa penjualan dengan nilai nol
                while ($currentDate <= $endDate) {
                    $formattedDate = $currentDate->format('d M');
                    if (!isset($sales[$formattedDate])) {
                        $sales[$formattedDate] = [];
                    }
                    $currentDate->addDay();
                }

                $sortedSales = $sales->toArray();
                ksort($sortedSales);
            }

            $color = ['#007bff', '#20c997', '#fd7e14', '#e83e8c', '#6f42c1'];

            $sortedSales = collect($sortedSales)->map(function ($items, $key) use (&$labels, $index) { // Menggunakan $key untuk mendapatkan kunci
                if ($index == 0) {
                    $labels[] = $key; // Menambahkan kunci ke dalam array labels
                }
                $totalAmount = collect($items)->sum('volume_penjualan'); // Hitung jumlah penjualan per bulan

                return $totalAmount;
            });
            $datasets[] = [
                'label' => $shop->nama,
                'data' => collect($sortedSales)->values(),
                'backgroundColor' => $color[$index]
            ];
        }

        $data = [
            'labels' => $labels,
            'datasets' => $datasets,
        ];

        return $data;
    }
}
