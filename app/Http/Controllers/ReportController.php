<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Shop;
use App\Models\Price;
use App\Models\Incoming;
use App\Models\TestPump;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{

    public static function calcLabaKotor($shop_id = 1, $start = null, $end = null)
    {
        if ($start == null) {
            $start = '2021-01-01';
        }
        if ($end == null) {
            $end = Carbon::now();
        }

        $sales_group = Sale::where('shop_id', $shop_id)->whereBetween('created_at', [$start, $end])->get()->groupBy('price_id');

        $reports = [];
        $price_ids = $sales_group->keys();
        $i = 0;
        $stok_awal = 142.85 * 21;
        foreach ($sales_group as $price_id => $sales) {

            $harga = Price::find($price_id);
            $harga_beli = $harga->harga_beli;
            $harga_jual = $harga->harga_jual;

            $penjualan_pertama = $sales->first();
            $penjualan_terakhir = $sales->last();

            // SISA STOK AKHIR 
            $stik_akhir = $penjualan_terakhir->stik_akhir;

            $incomings = Incoming::where('shop_id', $shop_id)
                ->whereBetween('created_at', [$start, $end])
                ->whereRelation('purchase', 'price_id', $price_id)->get();

            if ($incomings->where('created_at', '>',  $penjualan_terakhir->created_at)->count() > 0) {
                $stik_akhir = $incomings->last()['stik_akhir'];
            }

            $sisa_stok_akhir = $stik_akhir * 21;

            // PEMBELIAN 
            if ($i == 0) {
                $penjualan_sebelumnya = Sale::where('shop_id', $shop_id)
                    ->where('created_at', '<', $start)
                    ->latest()->first();
                $stik_sebelumya = $penjualan_sebelumnya ?  $penjualan_sebelumnya->stik_akhir * 21 : 142.85;
                $stok_awal =  $stik_sebelumya * 21;
                $stok_awal_harga_beli = floatval($penjualan_sebelumnya ?  $penjualan_sebelumnya->price->harga_beli : Price::where('created_at', '<', $penjualan_pertama->created_at)->latest()->first()->harga_beli);
                $stok_awal_rp = $stok_awal * $stok_awal_harga_beli;
            } else {
                $stok_awal_harga_beli = floatval(Price::find($price_ids[$i - 1])->harga_beli);
                $stok_awal_rp = $stok_awal *  $stok_awal_harga_beli;
            }


            $count_datang =  $incomings->count();
            $datang = $incomings->sum('jumlah');
            $jumlah_pembelian = $stok_awal + $datang;
            $jumlah_pembelian_rp = $stok_awal_rp + $datang * $harga_beli;

            // PENJUALAN 
            $totalisator_awal = floatval($penjualan_pertama->totalisator_awal); //a
            $totalisator_akhir = floatval($penjualan_terakhir->totalisator_akhir); //b

            $testPumps = TestPump::where('shop_id', $shop_id)
                ->where('created_at', '>', $penjualan_sebelumnya ? $penjualan_sebelumnya->created_at : '2020-01-01')
                ->where('created_at', '<=', $i == count($sales_group) - 1 ? $end : $penjualan_terakhir->created_at)
                ->get();

            $test_pump = $testPumps->sum('jumlah'); //d

            if ($testPumps->where('created_at', '>',  $penjualan_terakhir->created_at)->count() > 0) {
                $totalisator_akhir = $testPumps->last()['totalisator_akhir'];
            }

            $total_penjualan = $totalisator_akhir - $totalisator_awal; //c: (a-b)
            // $total_penjualan = $sales->sum('jumlah');


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
                'test_pump' => round($test_pump),
                'jumlah_penjualan' => round($jumlah_penjualan, 2),
                'jumlah_penjualan_bersih_rp' => round($jumlah_penjualan_bersih_rp, 2),
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
        return collect($reports);
    }


    public function laba_kotor()
    {

        $now = Carbon::now();
        $startOfMonth = $now->startOfMonth()->format('Y-m-d');
        $endOfMonth = $now->endOfMonth()->format('Y-m-d');
        $reports = self::calcLabaKotor(1, $startOfMonth, $endOfMonth);

        $data = [
            'reports' => $reports
        ];

        return view('report.laba_kotor', $data);
    }

    protected function getSummary($shop_id, $month)
    {
        $start = Carbon::createFromFormat('Y-m', $month)->startOfMonth()->format('Y-m-d');
        $end = Carbon::createFromFormat('Y-m', $month)->endOfMonth()->format('Y-m-d');

        $reports = ReportController::calcLabaKotor($shop_id, $start, $end);
        $summary = [
            'jumlah_penjualan_bersih' => $reports->sum('jumlah_penjualan_bersih_rp'),
            'jumlah_pembelian' => $reports->sum('jumlah_pembelian_rp'),
            'laba_kotor' => $reports->sum('laba_kotor'),
            'omset_harian' => $reports->count() > 0 ? $reports->sum('rata_rata_omset_harian') / $reports->count() : 0,
        ];

        return $summary;
    }

    public function index(Request $request)
    {

        if ($request->ajax()) {
            $shop_id = $request->input('shop_id', 1);

            $sales = Sale::where('shop_id', $shop_id)->get()->groupBy(function ($item) {
                return $item->created_at->format('Y-m');
            });

            $data = $sales->map(function ($value, $key) use ($shop_id) {
                $summary = $this->getSummary($shop_id, $key);

                return [
                    'shop_id' => $shop_id,
                    'bulan' => $key,
                    'laba_kotor' => $summary['laba_kotor'],
                    'laba_bersih' => 0,
                    'posisi_modal_kerja' => 0,
                ];
            });

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $button = '<a href="' . route('reports.show', ['shop_id' => $row['shop_id'], 'month' => $row['bulan']]) . '" class="btn btn-sm btn-success" title="Detail"><i class="fa fa-list mr-1"></i> Detail</a>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $shops = Shop::all();

        return view('report.index', compact('shops'));
    }

    public function show(String $shop_id, String $month)
    {
        
        $start = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $end = Carbon::createFromFormat('Y-m', $month)->endOfMonth();

        $reports = self::calcLabaKotor($shop_id, $start, $end);

        $shop = Shop::find($shop_id);

        return view('report.show', compact('shop', 'reports', 'start', 'end'));
    }
}
