<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Shop;
use App\Models\Price;
use App\Models\Incoming;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {

        $pertashop =  Shop::find(1);
        $harga = Price::latest()->first();
        $harga_beli = $harga->harga_beli;
        $harga_jual = $harga->harga_jual;

        // $penjualan_awal = Sale::whereMonth('created_at', Carbon::now()->month)->oldest()->first();
        // $penjualan_akhir = Sale::whereMonth('created_at', Carbon::now()->month)->latest()->first();
        // $penjualan_akhir = Sale::where('shop_id', 1)->latest()->first();

        // SISA STOK AKHIR 
        $stik_akhir = $pertashop->stik_akhir;
        $sisa_stok_akhir = $stik_akhir * 21;

        // PEMBELIAN 
        $penjualan_terakhir_bulan_lalu = Sale::whereMonth('created_at', '<', Carbon::now()->month)->latest()->first();
        $stik_akhir_bulan_lalu = $penjualan_terakhir_bulan_lalu ?  $penjualan_terakhir_bulan_lalu->stik_akhir * 21 : 142.85;
        $stok_awal =  $stik_akhir_bulan_lalu * 21;
        $datang = Incoming::where('shop_id', 1)->whereMonth('created_at', Carbon::now()->month)->get()->sum('jumlah');
        $jumlah_pembelian = $stok_awal + $datang;
        $jumlah_pembelian_rp = $jumlah_pembelian * $harga_beli;

        // PENJUALAN 
        // $totalisator_awal = $penjualan_awal->totalisator_awal; //a
        // $totalisator_akhir = $penjualan_akhir->totalisator_akhir; //b
        // $total_penjualan = $totalisator_akhir - $totalisator_awal; //c: (a-b)
        $sales = Sale::where('shop_id', 1)->whereMonth('created_at', Carbon::now()->month)->get();
        $total_penjualan = $sales->sum('jumlah');
        $test_pump = 0; //d

        $jumlah_penjualan = $total_penjualan - $test_pump; //B: (c-d)
        $jumlah_penjualan_rp = $jumlah_penjualan * $harga_jual;

        $sisa_stok = $jumlah_pembelian - $jumlah_penjualan; //(A-B)
        $sisa_stok_rp = $sisa_stok * $harga_beli;

        $jumlah_losses_gain =  $sisa_stok_akhir - $sisa_stok;


        $losses_gain = $jumlah_penjualan != 0 ? $jumlah_losses_gain / $jumlah_penjualan * 100 : 0;


        $jumlah_losses_gain_rp = $jumlah_losses_gain * $harga_beli;

        $jumlah_penjualan_bersih_rp = $jumlah_penjualan_rp + $sisa_stok_rp + $jumlah_losses_gain_rp;

        $laba_kotor = $jumlah_penjualan_bersih_rp - $jumlah_pembelian_rp;

        $jumlah_hari = $sales->groupBy(function ($item) {
            return $item->created_at->format('d/m/Y');
        })->count();

        $rata_rata_omset_harian = $jumlah_hari > 0 ? $jumlah_penjualan / $jumlah_hari : 0;

        $data = [
            'jumlah_penjualan' => $jumlah_penjualan,
            'jumlah_penjualan_bersih_rp' => $jumlah_penjualan_bersih_rp,
            'jumlah_pembelian_rp' => $jumlah_pembelian_rp,
            'laba_kotor' => $laba_kotor,
            'sisa_stok' => $sisa_stok,
            'sisa_stok_akhir' => $sisa_stok_akhir,
            'losses_gain' => $losses_gain,
            'jumlah_losses_gain' => $jumlah_losses_gain,
            'rata_rata_omset_harian' => $rata_rata_omset_harian,
            'shops' => Shop::all()
        ];

        return view('dashboard.index', $data);
    }

    public function data(Request $request)
    {
        $shop_id = $request->input('shop_id');
        $time_filter = $request->input('filter');

        $sales = $this->getSales($shop_id, $time_filter);

        $stocks = $this->getStock($shop_id);

        return response()->json([
            'stocks' => $stocks,
            'sales' => $sales
        ]);
    }

    public function getStock($shop_id)
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
                [
                    'label' => 'L/G',
                    'data' => [],
                    'backgroundColor' => [], // Warna latar belakang untuk setiap bar
                ],
            ]
        ];

        foreach ($shops as $shop) {
            $data['labels'][] = $shop->nama;
            $data['datasets'][1]['data'][] = 3000;
            $data['datasets'][0]['data'][] = $shop->stik_akhir * 21;
            $data['datasets'][2]['data'][] = Sale::where('shop_id', $shop->id)->latest()->first()?->losses_gain;
            $data['datasets'][1]['backgroundColor'][] = 'lightgray';
            // Tentukan warna berdasarkan kondisi stok kurang dari 1500
            if ($shop->stik_akhir * 21 < 1500) {
                $data['datasets'][0]['backgroundColor'][] = '#dc3545'; // Warna hijau jika stok cukup
            } else {
                $data['datasets'][0]['backgroundColor'][] = '#28a745'; // Warna merah jika stok kurang dari 1500
            }
        }


        return $data;
    }


    // protected function getSales($shop_id, $time_filter)
    // {
    //     $shops = Shop::all();

    //     if ($shop_id) {
    //         $shops = Shop::where('id', $shop_id)->get();
    //     }

    //     $data = [];
    //     foreach ($shops as $shop) {
    //         $sales = Sale::where('shop_id', $shop->id)->whereMonth('created_at', Carbon::now()->month)->oldest();
    //         if ($time_filter === 'week') {
    //             $startDateOfMonth = Carbon::now()->startOfMonth();
    //             $sales = $sales->where('created_at', '>=', $startDateOfMonth)->get()->groupBy(function ($item) use ($startDateOfMonth) {
    //                 $daysDiff = $item->created_at->diffInDays($startDateOfMonth);
    //                 $weekOfMonth = ceil(($daysDiff + $startDateOfMonth->dayOfWeek) / 7);
    //                 // $monthName = $startDateOfMonth->format('F');

    //                 return "Minggu ke-" . $weekOfMonth;
    //             });

    //             // Mengisi tanggal-tanggal tanpa penjualan dengan nilai nol
    //             $startDate = Carbon::now()->startOfMonth();
    //             $endDate = Carbon::now()->endOfMonth();
    //             $currentDate = $startDate;
    //             while ($currentDate <= $endDate) {
    //                 $daysDiff = $currentDate->diffInDays($startDateOfMonth);
    //                 $weekOfMonth = ceil(($daysDiff + $startDateOfMonth->dayOfWeek) / 7);
    //                 $formattedDate = "Minggu ke-" . $weekOfMonth;
    //                 if (!isset($sales[$formattedDate])) {
    //                     $sales[$formattedDate] = [];
    //                 }
    //                 $currentDate->addWeek();
    //             }

    //             $sortedSales = $sales->toArray();
    //             ksort($sortedSales);
    //         } elseif ($time_filter === 'month') {
    //             $sales = $sales->where('created_at', '>=', Carbon::now()->startOfYear())->get()->groupBy(function ($item) {
    //                 return $item->created_at->format('M Y');
    //             });

    //             $startMonth = Carbon::now()->startOfYear();
    //             $endMonth = Carbon::now()->endOfYear();
    //             $currentMonth = $startMonth;

    //             while ($currentMonth <= $endMonth) {
    //                 $formattedMonth = $currentMonth->format('M Y');
    //                 if (!isset($sales[$formattedMonth])) {
    //                     $sales[$formattedMonth] = [];
    //                 }
    //                 $currentMonth->addMonth();
    //             }

    //             $sortedSales = $sales->toArray();
    //             // Sort the array using the custom comparison function
    //             uksort($sortedSales, function ($a, $b) {
    //                 $dateA = Carbon::createFromFormat('M Y', $a);
    //                 $dateB = Carbon::createFromFormat('M Y', $b);

    //                 return $dateA < $dateB ? -1 : ($dateA > $dateB ? 1 : 0);
    //             });
    //         } else {
    //             $sales = $sales->get()->groupBy(function ($item) {
    //                 return $item->created_at->format('d M');
    //             });
    //             // Mengisi tanggal-tanggal tanpa penjualan dengan nilai nol
    //             $startDate = Carbon::now()->startOfMonth();
    //             $endDate = Carbon::now()->endOfMonth();
    //             $currentDate = $startDate;

    //             while ($currentDate <= $endDate) {
    //                 $formattedDate = $currentDate->format('d M');
    //                 if (!isset($sales[$formattedDate])) {
    //                     $sales[$formattedDate] = [];
    //                 }
    //                 $currentDate->addDay();
    //             }

    //             $sortedSales = $sales->toArray();
    //             ksort($sortedSales);
    //         }

    //         // Ubah objek koleksi menjadi array dan urutkan berdasarkan tanggal


    //         $data[$shop->nama] = $sortedSales;
    //     }

    //     return $data;




    //     // $sales = Sale::where('shop_id', 1)->whereMonth('created_at', Carbon::now()->month)->oldest();
    //     // if ($time_filter === 'week') {
    //     //     $sales = $sales->where('created_at', '>=', Carbon::now()->subDays(7))->get()->groupBy(function ($item) {
    //     //         return $item->created_at->format('d/m/Y');
    //     //     });
    //     // } elseif ($time_filter === 'month') {
    //     //     $sales = $sales->where('created_at', '>=', Carbon::now()->subMonth(6))->get()->groupBy(function ($item) {
    //     //         return $item->created_at->format('M Y');
    //     //     });
    //     // } else {
    //     //     $sales = $sales->get()->groupBy(function ($item) {
    //     //         return $item->created_at->format('Y-m-d');
    //     //     });
    //     // }

    //     // return response()->json($sales);
    // }

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



    // public function getStok()
    // {
    //     $product = Product::latest()->first();
    //     // PEMBELIAN 
    //     $stok_awal = $product->stok_awal;
    //     $jumlah_pembelian = $stok_awal;

    //     // PENJUALAN 
    //     $total_penjualan = Sale::get()->sum('jumlah');
    //     $jumlah_penjualan = $total_penjualan;

    //     $sisa_stok = $jumlah_pembelian - $jumlah_penjualan;
    //     $losses_gain = Sale::get()->sum('losses_gain');
    //     $jumlah_losses_gain = $losses_gain / 100 * $jumlah_penjualan;

    //     // SISA STOK AKHIR 
    //     $sisa_stok_akhir = $sisa_stok + $jumlah_losses_gain;

    //     return response()->json([
    //         'stok_akhir_teoritis' => $sisa_stok,
    //         'stok_akhir_aktual' => $sisa_stok_akhir,
    //     ]);
    // }
}
