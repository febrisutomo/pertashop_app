<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Price;
use App\Models\Spending;
use App\Models\DailyReport;
use App\Models\Incoming;
use App\Models\TestPump;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class DailyReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            if (Auth::user()->role == 'admin') {
                $shop_id = Auth::user()->admin->shop_id;
            } elseif (Auth::user()->role == 'operator') {
                $shop_id = Auth::user()->operator->shop_id;
            } else {
                $shop_id = $request->input('shop_id', 1);
            }

            $data = DailyReport::with(['operator.user', 'spendings', 'incomings', 'testPumps'])->where('shop_id', $shop_id)->latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($data) {
                    if (Auth::user()->role == 'operator') {
                        $lastRow = $data->first(); // Mendapatkan data terakhir dari koleksi
                        $button = '';

                        if ($row->id === $lastRow->id && $row->operator_id === Auth::user()->operator->id) { // Menambahkan tombol hanya pada data terakhir
                            $button = '<a href="' . route('daily-reports.edit', $row->id) . '" class="btn btn-sm btn-info" title="Edit"><i class="fa fa-edit"></i></a>';
                            $button .= ' <button class="btn btn-sm btn-danger btn-delete" title="hapus" data-id="' . $row->id . '"><i class="fa fa-trash"></i></button>';
                        }
                    } else {
                        $button = '<a href="' . route('daily-reports.edit', $row->id) . '" class="btn btn-sm btn-info" title="Edit"><i class="fa fa-edit"></i></a>';
                        $button .= ' <button class="btn btn-sm btn-danger btn-delete" title="hapus" data-id="' . $row->id . '"><i class="fa fa-trash"></i></button>';
                    }

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $shops = Shop::all();

        return view('daily_report.index', compact('shops'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {

        $harga = Price::latest()->first()->harga_jual;
        #get shop id for operator login
        $shop_id = Auth::user()->operator->shop_id;

        $shop = Shop::find($shop_id);

        $operator_id = Auth::user()->operator->id;

        $latest_daily_report = DailyReport::where('shop_id', $shop_id)
            ->latest()
            ->orderBy('totalisator_akhir', 'desc')
            ->first();
        $totalisator_awal =  $latest_daily_report ? $latest_daily_report->totalisator_akhir : Shop::find($shop_id)->totalisator_awal;
        $stik_awal =  $latest_daily_report ? $latest_daily_report->stik_akhir : Shop::find($shop_id)->stik_awal;

        $latest_daily_report_by_operator = DailyReport::where('operator_id', $operator_id)
            ->latest()
            ->orderBy('totalisator_akhir', 'desc')
            ->first();

        $belum_disetorkan = $latest_daily_report_by_operator ? $latest_daily_report_by_operator->belum_disetorkan : 0;

        //ajax get total spendings where operator_id = $operator_id and created_at = now() and $daily_report_id = null
        if ($request->ajax()) {
            $spendings = Spending::whereDate('created_at', Carbon::now()->format('Y-m-d'))
                ->where('operator_id', $operator_id)
                ->where('daily_report_id', null)
                ->get();
            $incomings = Incoming::whereDate('created_at', Carbon::now()->format('Y-m-d'))
                ->where('operator_id', $operator_id)
                ->where('daily_report_id', null)
                ->get();
            $testPumps = TestPump::whereDate('created_at', Carbon::now()->format('Y-m-d'))
                ->where('operator_id', $operator_id)
                ->where('daily_report_id', null)
                ->get();
            $total_spendings = $spendings->sum('jumlah');
            $total_incomings = $incomings->sum('volume');
            $total_test_pumps = $testPumps->sum('volume');
            return response()->json(compact('total_spendings', 'total_incomings', 'total_test_pumps'));
        }

        return view('daily_report.create', compact('harga', 'shop', 'totalisator_awal', 'stik_awal', 'belum_disetorkan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validate request
        $customMessages = [
            'required' => ':attribute wajib diisi.',
        ];

        $validated = $request->validate([
            'totalisator_awal' => 'required|numeric',
            'totalisator_akhir' => 'required|numeric',
            'stik_awal' => 'required|numeric',
            'stik_akhir' => 'required|numeric',
            'disetorkan' => 'required|numeric'
        ], $customMessages);

        //get shop id for admin or operator login
        if (Auth::user()->role == 'admin') {
            $shop_id = Auth::user()->admin->shop_id;
        } elseif (Auth::user()->role == 'operator') {
            $shop_id = Auth::user()->operator->shop_id;
        } else {
            $shop_id = 1;
        }

        $operator_id = Auth::user()->operator->id;
        $validated['shop_id'] = $shop_id;
        $validated['operator_id'] = $operator_id;
        $validated['created_at'] = Carbon::now()->format('Y-m-d H:i:s');
        $validated['price_id'] = Price::latest()->first()->id;

        //create daily report
        $dailyReport = DailyReport::create($validated);

        //update spendings where operator_id = $operator_id and created_at = now() and $daily_report_id = null
        Spending::whereDate('created_at', Carbon::now()->format('Y-m-d'))
            ->where('operator_id', $operator_id)
            ->where('daily_report_id', null)
            ->update(['daily_report_id' => $dailyReport->id]);

        //update incomings where operator_id = $operator_id and created_at = now() and $daily_report_id = null
        Incoming::whereDate('created_at', Carbon::now()->format('Y-m-d'))
            ->where('operator_id', $operator_id)
            ->where('daily_report_id', null)
            ->update(['daily_report_id' => $dailyReport->id]);

        //update test_pumps where operator_id = $operator_id and created_at = now() and $daily_report_id = null
        TestPump::whereDate('created_at', Carbon::now()->format('Y-m-d'))
            ->where('operator_id', $operator_id)
            ->where('daily_report_id', null)
            ->update(['daily_report_id' => $dailyReport->id]);

        return to_route('daily-reports.index')->with('success', 'Data laporan harian berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(DailyReport $dailyReport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, DailyReport $dailyReport)
    {
        if ($request->ajax()) {
            $spendings = Spending::whereDate('created_at', $dailyReport->created_at->format('Y-m-d'))
                ->where('operator_id', $dailyReport->operator->id)
                ->where('daily_report_id', null)
                ->get();
            $incomings = Incoming::whereDate('created_at', $dailyReport->created_at->format('Y-m-d'))
                ->where('operator_id', $dailyReport->operator->id)
                ->where('daily_report_id', null)
                ->get();
            $testPumps = TestPump::whereDate('created_at', $dailyReport->created_at->format('Y-m-d'))
                ->where('operator_id', $dailyReport->operator->id)
                ->where('daily_report_id', null)
                ->get();
            $total_spendings = $dailyReport->pengeluaran + $spendings->sum('jumlah');
            $total_incomings = $dailyReport->penerimaan + $incomings->sum('volume');
            $total_test_pumps = $dailyReport->test_pump + $testPumps->sum('volume');
            return response()->json(compact('total_spendings', 'total_incomings', 'total_test_pumps'));
        }
        // dd($dailyReport->pengeluaran);
        return view('daily_report.edit', compact('dailyReport'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DailyReport $dailyReport)
    {
        $customMessages = [
            'required' => ':attribute wajib diisi.',
        ];

        $validated = $request->validate([
            'totalisator_awal' => 'required|numeric',
            'totalisator_akhir' => 'required|numeric',
            'stik_awal' => 'required|numeric',
            'stik_akhir' => 'required|numeric',
            'disetorkan' => 'required|numeric',
            'diverifikasi' => 'boolean'
        ], $customMessages);

        $operator_id = $dailyReport->operator->id;

        //update daily report
        $dailyReport->update($validated);

        //update spendings where operator_id = $operator_id and created_at = now() and $daily_report_id = null
        Spending::whereDate('created_at', $dailyReport->created_at->format('Y-m-d'))
            ->where('operator_id', $operator_id)
            ->where('daily_report_id', null)
            ->update(['daily_report_id' => $dailyReport->id]);

        //update incomings where operator_id = $operator_id and created_at = now() and $daily_report_id = null
        Incoming::whereDate('created_at', $dailyReport->created_at->format('Y-m-d'))
            ->where('operator_id', $operator_id)
            ->where('daily_report_id', null)
            ->update(['daily_report_id' => $dailyReport->id]);

        //update test_pumps where operator_id = $operator_id and created_at = now() and $daily_report_id = null
        TestPump::whereDate('created_at', $dailyReport->created_at->format('Y-m-d'))
            ->where('operator_id', $operator_id)
            ->where('daily_report_id', null)
            ->update(['daily_report_id' => $dailyReport->id]);

        return to_route('daily-reports.index')->with('success', 'Data laporan harian berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DailyReport $dailyReport)
    {
        $dailyReport->delete();
        return response()->json([
            'message' => 'Data laporan harian telah dihapus.'
        ]);
    }
}
