<?php

namespace App\Http\Controllers;

use App\Models\DailyReport;
use App\Models\Shop;
use App\Models\Price;
use App\Models\Spending;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{


    public static function calcLabaKotor($shop_id = 1, $year_month = null)
    {
        if ($year_month == null) {
            $year_month = Carbon::now()->format('Y-m');
        }

        list($year, $month) = explode("-", $year_month);

        $daily_reports_group = DailyReport::where('shop_id', $shop_id)->whereMonth('created_at', $month)->whereYear('created_at', $year)->get()->groupBy('price_id');

        $reports = [];
        $i = 0;
        $stok_awal = Shop::find($shop_id)->kapasitas;
        foreach ($daily_reports_group as $price_id => $daily_reports) {

            $harga = Price::find($price_id);
            $harga_beli = $harga->harga_beli;
            $harga_jual = $harga->harga_jual;

            // SISA STOK AKHIR 
            $stik_akhir = $daily_reports->last()->stik_akhir;

            $sisa_stok_akhir = $daily_reports->last()->stok_akhir_aktual;

            // PEMBELIAN 
            if ($i == 0) {
                $daily_report_sebelumnya = DailyReport::where('shop_id', $shop_id)
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', '<', $month)
                    ->latest()->first();
                $stok_awal = $daily_report_sebelumnya ? $daily_report_sebelumnya->stok_akhir_aktual : Shop::find($shop_id)->kapasitas;
                $stok_awal_harga_beli = $daily_report_sebelumnya ?  $daily_report_sebelumnya->price->harga_beli : Price::where('created_at', '<', $daily_reports->first()->created_at)->latest()->first()->harga_beli;
            } else {
                $stok_awal_harga_beli = Price::where('created_at', '<', $daily_reports->first()->created_at)->latest()->first()->harga_beli;
            }

            $stok_awal_rp = $stok_awal *  $stok_awal_harga_beli;

            $count_datang =  $daily_reports->count('penerimaan');
            $datang = $daily_reports->sum('penerimaan');
            $jumlah_pembelian = $stok_awal + $datang;
            $jumlah_pembelian_rp = $stok_awal_rp + $datang * $harga_beli;

            // PENJUALAN 
            $totalisator_awal = $daily_reports->first()->totalisator_awal; //a
            $totalisator_akhir = $daily_reports->last()->totalisator_akhir; //b

            $test_pump = $daily_reports->sum('test_pump'); //d


            $total_penjualan = $totalisator_akhir - $totalisator_awal; //c: (a-b)
            // $total_penjualan = $daily_reports->sum('jumlah');

            $jumlah_penjualan = $total_penjualan - $test_pump; //B: (c-d)
            $jumlah_penjualan_rp = $jumlah_penjualan * $harga_jual;

            $sisa_stok = $jumlah_pembelian - $jumlah_penjualan; //(A-B)
            $sisa_stok_rp = $sisa_stok * $harga_beli;

            $losses_gain =  $sisa_stok_akhir - $sisa_stok;

            $persen_losses_gain = $jumlah_penjualan != 0 ? $losses_gain / $jumlah_penjualan * 100 : 0;

            $losses_gain_rp = $losses_gain * $harga_beli;

            $jumlah_penjualan_bersih_rp = $jumlah_penjualan_rp + $sisa_stok_rp + $losses_gain_rp;

            $laba_kotor = $jumlah_penjualan_bersih_rp - $jumlah_pembelian_rp;

            //get total days in certain month
            $jumlah_hari = Carbon::createFromFormat('Y-m', $year_month)->daysInMonth;

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

        $year_month = Carbon::now()->format('Y-m');
        $reports = self::calcLabaKotor(1, $year_month);

        $data = [
            'reports' => $reports
        ];

        return view('report.laba_kotor', $data);
    }

    public static function getSummary($shop_id, $year_month = null)
    {

        if ($year_month == null) {
            $year_month = Carbon::now()->format('Y-m');
        }

        $reports = ReportController::calcLabaKotor($shop_id, $year_month);

        $summary = [
            'jumlah_penjualan_bersih_rp' => $reports->sum('jumlah_penjualan_bersih_rp'),
            'jumlah_pembelian_rp' => $reports->sum('jumlah_pembelian_rp'),
            'laba_kotor' => $reports->sum('laba_kotor'),
            'rata_rata_omset_harian' => $reports->count() > 0 ? $reports->sum('rata_rata_omset_harian') / $reports->count() : 0,
        ];

        return $summary;
    }

    public function index(Request $request)
    {

        if ($request->ajax()) {
            $shop_id = $request->input('shop_id', 1);

            $sales = DailyReport::where('shop_id', $shop_id)->get()->groupBy(function ($item) {
                return $item->created_at->format('Y-m');
            });

            $data = $sales->map(function ($value, $key) use ($shop_id) {
                $summary = self::getSummary($shop_id, $key);

                // hitung laba bersih financial
                list($year, $month) = explode("-", $key);
                $total_biaya = DailyReport::where('shop_id', $shop_id)
                    ->whereMonth('created_at', $month)
                    ->whereYear('created_at', $year)
                    ->get()->sum('pengeluaran');

                $laba_bersih = $summary['laba_kotor'] - $total_biaya;
                $alokasi_dana_tak_terduga = 10 / 100 * $laba_bersih;
                $laba_bersih_financial = $laba_bersih - $alokasi_dana_tak_terduga;

                return [
                    'shop_id' => $shop_id,
                    'bulan' => $key,
                    'laba_kotor' => $summary['laba_kotor'],
                    'laba_bersih' => $laba_bersih_financial,
                    'posisi_modal_kerja' => 0,
                ];
            });

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $button = '<a href="' . route('reports.laba_kotor', ['shop_id' => $row['shop_id'], 'year_month' => $row['bulan']]) . '" class="btn btn-sm btn-success" title="Detail"><i class="fa fa-list mr-1"></i> Detail</a>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $shops = Shop::all();

        return view('report.index', compact('shops'));
    }

    public function labaKotor(string $shop_id, string $year_month)
    {

        $reports = self::calcLabaKotor($shop_id, $year_month);

        $shop = Shop::find($shop_id);

        $date = Carbon::createFromFormat('Y-m', $year_month);

        return view('report.laba_kotor', compact('shop', 'reports', 'date'));
    }

    public function labaBersih(string $shop_id, string $year_month)
    {
        $summary = self::getSummary($shop_id, $year_month);

        $laba_kotor = $summary['laba_kotor'];

        $shop = Shop::find($shop_id);

        list($year, $month) = explode("-", $year_month);
        $spendings = Spending::with(['shop'])
            ->where('shop_id', $shop_id)
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->get();

        $total_biaya = $spendings->sum('jumlah');
        $laba_bersih = $laba_kotor - $total_biaya;
        $alokasi_dana_tak_terduga = 10 / 100 * $laba_bersih;
        $laba_bersih_financial = $laba_bersih - $alokasi_dana_tak_terduga;

        $date = Carbon::createFromFormat('Y-m', $year_month);

        return view('report.laba_bersih', compact('shop', 'laba_kotor', 'date', 'spendings', 'total_biaya', 'laba_bersih', 'alokasi_dana_tak_terduga', 'laba_bersih_financial'));
    }
}
