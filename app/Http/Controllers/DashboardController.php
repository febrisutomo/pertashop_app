<?php

namespace App\Http\Controllers;

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
            $shop_id = $request->input('shop_id');

            if (Auth::user()->role == 'operator') {
                $shop_id = Auth::user()->operator->shop->id;
            }

            $time_filter = $request->input('filter');

            $sales = $this->getSales($shop_id, $time_filter);

            $stocks = $this->getStock($shop_id);

            $summary = $this->getSummary($shop_id);

            return response()->json([
                'stocks' => $stocks,
                'sales' => $sales,
                'summary' => $summary
            ]);
        }
        $reports = ReportController::calcLabaKotor(1);

        $data = [
            'reports' => $reports,
            'shops' => Shop::all()
        ];

        return view('dashboard.index', $data);
    }

    protected function getSummary($shop_id)
    {
        $shops = Shop::all();

        if ($shop_id) {
            $shops = Shop::where('id', $shop_id)->get();
        }

        $data = [];
        $now = Carbon::now();
        $start = $now->startOfMonth()->format('Y-m-d');
        $end = $now->endOfMonth()->format('Y-m-d');

        $count_omset = 0;
        foreach ($shops as $shop) {
            $reports = ReportController::calcLabaKotor($shop->id, $start, $end);
            $data[] = [
                'jumlah_penjualan_bersih_rp' => $reports->sum('jumlah_penjualan_bersih_rp'),
                'jumlah_pembelian_rp' => $reports->sum('jumlah_pembelian_rp'),
                'laba_kotor' => $reports->sum('laba_kotor'),
                'rata_rata_omset_harian' => $reports->count() > 0 ? $reports->sum('rata_rata_omset_harian') / $reports->count() : 0,
            ];
            if ($reports->sum('rata_rata_omset_harian') > 0) {
                $count_omset++;
            }
        }

        $data = collect($data);
        $summary = [
            'jumlah_penjualan_bersih' => $data->sum('jumlah_penjualan_bersih_rp'),
            'jumlah_pembelian' => $data->sum('jumlah_pembelian_rp'),
            'laba_kotor' => $data->sum('laba_kotor'),
            'omset_harian' => $count_omset > 0 ? $data->sum('rata_rata_omset_harian') / $count_omset : 0,
        ];

        return $summary;
    }

    protected function getStock($shop_id)
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
        $now = Carbon::now();
        $start = $now->startOfMonth()->format('Y-m-d');
        $end = $now->endOfMonth()->format('Y-m-d');
        foreach ($shops as $shop) {
            $data['labels'][] = $shop->nama;
            $stik_akhir = ReportController::calcLabaKotor($shop->id)->last() ? ReportController::calcLabaKotor($shop->id, $start, $end)->last()['stik_akhir'] : 142.85;
            $data['datasets'][0]['data'][] = $stik_akhir * 21;
            $data['datasets'][1]['data'][] = 3000;
            // Tentukan warna berdasarkan kondisi stok kurang dari 1500
            if ($stik_akhir * 21 < 1500) {
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

            $sales = Sale::where('shop_id', $shop->id)->whereMonth('created_at', Carbon::now()->month)->oldest();
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
                $totalAmount = collect($items)->sum('jumlah'); // Hitung jumlah penjualan per bulan

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
