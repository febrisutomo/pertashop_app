<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Price;
use App\Models\Incoming;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function laba_kotor()
    {

        $sales_group = Sale::where('shop_id', 1)->whereMonth('created_at', Carbon::now()->month)->get()->groupBy('price_id');

        $reports = [];
        // dd($price_ids->diff(['12500.00']));
        $price_ids = $sales_group->keys();
        $i = 0;
        $stok_awal = 142.85 * 21;
        foreach ($sales_group as $price_id => $sales) {
            // $penjualan_awal = Sale::whereMonth('created_at', Carbon::now()->month)->oldest()->first();
            // $penjualan_akhir = Sale::whereMonth('created_at', Carbon::now()->month)->latest()->first();
            // $penjualan_akhir = Sale::where('shop_id', 1)->latest()->first();

            $harga = Price::find($price_id);
            $harga_beli = $harga->harga_beli;
            $harga_jual = $harga->harga_jual;

            // SISA STOK AKHIR 
            $stik_akhir = $sales[$sales->count() - 1]->stik_akhir;
            $sisa_stok_akhir = $stik_akhir * 21;

            // PEMBELIAN 
            if ($i == 0) {
                $penjualan_sebelumnya = Sale::whereMonth('created_at', '<', Carbon::now()->month)->latest()->first();
                $stik_sebelumya = $penjualan_sebelumnya ?  $penjualan_sebelumnya->stik_akhir * 21 : 142.85;
                $stok_awal =  $stik_sebelumya * 21;
                $stok_awal_harga_beli = floatval($penjualan_sebelumnya ?  $penjualan_sebelumnya->price->harga_beli : Price::where('created_at', '<', $sales[0]->created_at)->latest()->first()->harga_beli);
                $stok_awal_rp = $stok_awal * $stok_awal_harga_beli;
            } else {
                $stok_awal_harga_beli = floatval(Price::find($price_ids[$i - 1])->harga_beli);
                $stok_awal_rp = $stok_awal *  $stok_awal_harga_beli;
            }

            $count_datang =  Incoming::where('shop_id', 1)->whereMonth('created_at', Carbon::now()->month)->whereRelation('purchase', 'price_id', $price_id)->get()->count();
            $datang = Incoming::where('shop_id', 1)->whereMonth('created_at', Carbon::now()->month)->whereRelation('purchase', 'price_id', $price_id)->get()->sum('jumlah');
            $jumlah_pembelian = $stok_awal + $datang;
            $jumlah_pembelian_rp = $stok_awal_rp + $datang * $harga_beli;

            // PENJUALAN 
            $totalisator_awal = floatval($sales[0]->totalisator_awal); //a
            $totalisator_akhir = floatval($sales[$sales->count() - 1]->totalisator_akhir); //b
            $total_penjualan = $totalisator_akhir - $totalisator_awal; //c: (a-b)
            // $total_penjualan = $sales->sum('jumlah');
            $test_pump = 0; //d

            $jumlah_penjualan = $total_penjualan - $test_pump; //B: (c-d)
            $jumlah_penjualan_rp = $jumlah_penjualan * $harga_jual;

            $sisa_stok = $jumlah_pembelian - $jumlah_penjualan; //(A-B)
            $sisa_stok_rp = $sisa_stok * $harga_beli;

            $losses_gain =  $sisa_stok_akhir - $sisa_stok;

            $persen_losses_gain = $jumlah_penjualan != 0 ? $losses_gain / $jumlah_penjualan * 100 : 0;


            $losses_gain_rp = $losses_gain * $harga_beli;

            $jumlah_penjualan_bersih_rp = $jumlah_penjualan_rp + $sisa_stok_rp + $losses_gain_rp;

            $laba_kotor = $jumlah_penjualan_bersih_rp - $jumlah_pembelian_rp;

            $jumlah_hari = $sales->groupBy(function ($item) {
                return $item->created_at->format('d/m/Y');
            })->count();

            $rata_rata_omset_harian = $jumlah_hari > 0 ? $jumlah_penjualan / $jumlah_hari : 0;


            $reports[] = [
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
                'jumlah_penjualan' => round($jumlah_penjualan, 2),
                'jumlah_penjualan_bersih_rp' => $jumlah_penjualan_bersih_rp,
                'sisa_stok' => round($sisa_stok, 2),
                'persen_losses_gain' => abs(round($persen_losses_gain, 3)),
                'losses_gain' => abs(round($losses_gain, 2)),
                'stik_akhir' => round($stik_akhir, 2),
                'sisa_stok_akhir' => round($sisa_stok_akhir, 2),
                'laba_kotor' => round($laba_kotor, 2),
                'rata_rata_omset_harian' => round($rata_rata_omset_harian, 2),
            ];
            $stok_awal = $sisa_stok_akhir;
            $i++;
        }

        $data = [
            'reports' => $reports
        ];

        return view('report.laba_kotor', $data);
    }
}
