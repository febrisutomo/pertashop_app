<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Price;
use App\Models\DailyReport;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class LabaKotorController extends Controller
{
    public static function getLabaKotor($shop_id = 1, $year_month = null)
    {
        if ($year_month == null) {
            $year_month = Carbon::now()->format('Y-m');
        }

        list($year, $month) = explode("-", $year_month);

        $reports = DailyReport::where('shop_id', $shop_id)->whereMonth('created_at', $month)->whereYear('created_at', $year)->get();
        $reports_group = $reports->groupBy('price_id');

        $data = [];
        $report_sebelumnya = DailyReport::where('shop_id', $shop_id)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', '<', $month)
            ->latest()->first();
        foreach ($reports_group as $price_id => $reports) {

            $price = Price::find($price_id);
            $harga_beli = $price->harga_beli;
            $harga_jual = $price->harga_jual;

            // SISA STOK AKHIR 
            $stik_akhir = $reports->last()->stik_akhir;

            $sisa_stok_akhir = $reports->last()->stok_akhir_aktual ?? $reports->last()->stok_akhir_teoritis;


            $stok_awal = $report_sebelumnya ? $report_sebelumnya->stok_akhir_aktual : Shop::find($shop_id)->stok_awal;
            $stok_awal_harga_beli = $report_sebelumnya ?  $report_sebelumnya->price->harga_beli : Price::where('created_at', '<', $year_month . "-01")->latest()->first()->harga_beli;

            $report_sebelumnya = $reports->last();

            $stok_awal_rp = $stok_awal *  $stok_awal_harga_beli;

            $datang = $reports->sum('penerimaan');
            $count_datang =  $datang / 2000;
            $jumlah_pembelian = $stok_awal + $datang;
            $jumlah_pembelian_rp = $stok_awal_rp + $datang * $harga_beli;

            // PENJUALAN 
            $totalisator_awal = $reports->first()->totalisator_awal; //a
            $totalisator_akhir = $reports->last()->totalisator_akhir; //b

            $test_pump = $reports->sum('percobaan'); //d


            // $total_penjualan = $totalisator_akhir - $totalisator_awal; //c: (a-b)
            $total_penjualan = $reports->sum('volume_penjualan');

            $jumlah_penjualan = $total_penjualan - $test_pump; //B: (c-d)
            // $jumlah_penjualan_rp = $jumlah_penjualan * $harga_jual;
            $jumlah_penjualan_rp = $reports->sum('rupiah_penjualan');

            dd($jumlah_penjualan_rp);
            $sisa_stok = $jumlah_pembelian - $jumlah_penjualan; //(A-B)
            $sisa_stok_rp = $sisa_stok * $harga_beli;

            $losses_gain =  $reports->sum('losses_gain');

            $persen_losses_gain = $jumlah_penjualan == 0 ? 0 : $losses_gain / $jumlah_penjualan * 100;

            $losses_gain_rp = $losses_gain * $harga_beli;

            $jumlah_penjualan_bersih_rp = $jumlah_penjualan_rp + $sisa_stok_rp + $losses_gain_rp;

            $laba_kotor = $jumlah_penjualan_bersih_rp - $jumlah_pembelian_rp;

            //get total days sale
            $jumlah_hari = $reports->groupBy('tanggal')->count();

            $rata_rata_omset_harian = $jumlah_hari == 0 ? 0 : $jumlah_penjualan / $jumlah_hari;
            $rata_rata_omset_harian_rp = $jumlah_hari == 0 ? 0 : $jumlah_penjualan_rp / $jumlah_hari;


            $data[] = [
                'harga_beli' => $harga_beli,
                'harga_jual' => $harga_jual,
                'stok_awal' => $stok_awal,
                'stok_awal_harga_beli' => $stok_awal_harga_beli,
                'datang' => $datang,
                'count_datang' => $count_datang,
                'jumlah_pembelian' => $jumlah_pembelian,
                'jumlah_pembelian_rp' => round($jumlah_pembelian_rp, 2),
                'totalisator_akhir' => round($totalisator_akhir, 2),
                'totalisator_awal' => round($totalisator_awal, 2),
                'total_penjualan' => round($total_penjualan, 2),
                'test_pump' => round($test_pump),
                'jumlah_penjualan' => round($jumlah_penjualan, 2),
                'jumlah_penjualan_rp' => round($jumlah_penjualan_rp, 2),
                'jumlah_penjualan_bersih_rp' => round($jumlah_penjualan_bersih_rp, 2),
                'sisa_stok' => round($sisa_stok, 2),
                'persen_losses_gain' => abs(round($persen_losses_gain, 3)),
                'losses_gain' => abs(round($losses_gain, 2)),
                'stik_akhir' => round($stik_akhir, 2),
                'sisa_stok_akhir' => round($sisa_stok_akhir, 2),
                'laba_kotor' => round($laba_kotor, 2),
                'rata_rata_omset_harian' => round($rata_rata_omset_harian, 2),
                'rata_rata_omset_harian_rp' => round($rata_rata_omset_harian_rp),
            ];
        }
        return collect($data);
    }

    public static function getLabaKotorFinal($shop_id, $year_month = null)
    {

        if ($year_month == null) {
            $year_month = Carbon::now()->format('Y-m');
        }

        $reports = self::getLabaKotor($shop_id, $year_month);

        $data = [
            'sisa_stok_akhir' => $reports->last() ? $reports->last()['sisa_stok_akhir'] : Shop::find($shop_id)->stok_awal,
            'jumlah_penjualan_bersih_rp' => $reports->sum('jumlah_penjualan_bersih_rp'),
            'jumlah_pembelian_rp' => $reports->sum('jumlah_pembelian_rp'),
            'laba_kotor' => $reports->sum('laba_kotor'),
            'rata_rata_omset_harian' => $reports->count() > 0 ? $reports->sum('rata_rata_omset_harian') / $reports->count() : 0,
            'rata_rata_omset_harian_rp' => $reports->count() > 0 ? $reports->sum('rata_rata_omset_harian_rp') / $reports->count() : 0,
        ];

        return $data;
    }

    public function index(Request $request)
    {

        if (Auth::user()->role == 'admin') {
            $shop_id = Auth::user()->shop_id;
        } else {
            $shop_id = $request->input('shop_id', 1);
        }

        $sales = DailyReport::where('shop_id', $shop_id)->get()->groupBy(function ($item) {
            return $item->created_at->format('Y-m');
        });

        $labaKotors = $sales->map(function ($value, $key) use ($shop_id) {
            $labaKotor = self::getLabaKotorFinal($shop_id, $key);

            $labaKotor['shop_id'] = $shop_id;
            $labaKotor['bulan'] = $key;

            return $labaKotor;
        });

        $shops = Shop::all();

        if (Auth::user()->role == 'investor') {
            $shops = Auth::user()->investments;
        }

        return view('laba_kotor.index', compact('shops', 'labaKotors'));
    }

    public function edit(string $shop_id, string $year_month)
    {

        $reports = self::getLabaKotor($shop_id, $year_month);

        $shop = Shop::find($shop_id);

        $date = Carbon::createFromFormat('Y-m', $year_month);

        return view('laba_kotor.edit', compact('shop', 'reports', 'date'));
    }
}
