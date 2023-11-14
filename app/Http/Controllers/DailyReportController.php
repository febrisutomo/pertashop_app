<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\User;
use App\Models\Price;
use App\Models\Vendor;
use App\Models\Incoming;
use App\Models\Purchase;
use App\Models\Spending;
use App\Models\TestPump;
use App\Models\DailyReport;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use App\Models\SpendingCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class DailyReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if (Auth::user()->role == 'super-admin') {
            $shop_id = $request->input('shop_id', 1);
        } else {
            $shop_id = Auth::user()->shop_id;
        }

        $year_month = $request->input('year_month', Carbon::now()->format('Y-m'));
        list($year, $month) = explode('-', $year_month);


        if (Auth::user()->role == 'operator') {
            //reports group by tanggal and paginate

            $reports = DailyReport::with(['operator'])
                ->where('shop_id', $shop_id)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->latest()
                ->get()
                ->groupBy('tanggal_panjang');

            $perPage = 5; // Adjust the number of records per page as needed
            $page = request()->input('page', 1); // Get the current page from the request

            $offset = ($page - 1) * $perPage;

            // Extract items for the current page without modifying the original grouped collection
            $items = array_slice($reports->all(), $offset, $perPage);

            // Create a LengthAwarePaginator instance
            $paginator = new LengthAwarePaginator($items, $reports->count(), $perPage, $page);

            // You can customize the pagination view or use the default view
            $paginator->withPath(route('daily-reports.index')); // Replace 'your /url' with your pagination URL

            return view('daily_report.index-operator', ['reports' => $paginator]);
        } else {
            $shops = Shop::all();

            $shop = Shop::find($shop_id);

            $reports = DailyReport::with(['operator', 'spendings', 'incoming', 'testPump'])->where('shop_id', $shop_id)->whereYear('created_at', $year)->whereMonth('created_at', $month)->oldest()->get();
            return view('daily_report.index', compact('shops', 'shop', 'reports'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $shop_id = $request->input('shop_id', 1);
        if (Auth::user()->role != 'super-admin') {
            $shop_id = Auth::user()->shop_id;
        }

        $shops = Shop::all();
        $operators = User::where('role', 'operator')->where('shop_id', $shop_id)->get();
        $purchases = Purchase::where('shop_id', $shop_id)->get()->filter(function ($purchase) {
            return $purchase->sisa > 0;
        });

        $categories = SpendingCategory::all();

        $vendors = Vendor::all();

        return view('daily_report.create', compact('shops', 'shop_id', 'operators', 'purchases', 'categories', 'vendors'));
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
            'shop_id' =>   [
                Rule::requiredIf(function () {
                    return Auth::user()->role == 'super-admin';
                }),
            ],
            'operator_id' =>  [
                Rule::requiredIf(function () {
                    return Auth::user()->role == 'super-admin';
                }),
            ],
            'tanggal' => 'required|date',
            'jam' => 'required',
            'price_id' => 'required|numeric',
            'totalisator_awal' => 'required|numeric',
            'totalisator_akhir' => 'required|numeric',
            'stik_awal' => 'required|numeric',
            'stik_akhir' => 'nullable',
            'setor_tunai' => 'nullable',
            'setor_qris' => 'nullable',
            'setor_transfer' => 'nullable'
        ], $customMessages);


        //get shop_id and operator_id for operator login
        if (Auth::user()->role == 'operator') {
            $validated['shop_id'] = Auth::user()->shop->id;
            $validated['operator_id'] = Auth::user()->id;
        }

        $validated['created_at'] = Carbon::parse($validated['tanggal'] . ' ' . $validated['jam']);

        try {
            DB::beginTransaction();

            //create daily report
            $dailyReport = DailyReport::create($validated);

            //update daily report id for spending, incoming, and test pump
            //check input test_pump not null
            if ($request->input('test_pump') != null && $request->input('test_pump') != 0) {
                TestPump::create([
                    'daily_report_id' => $dailyReport->id,
                    'volume_test' => $request->input('volume_test'),
                    'volume_aktual' => $request->input('test_pump'),
                ]);
            }

            //check input penerimaan not null
            if ($request->input('penerimaan') != null && $request->input('penerimaan') != 0) {
                Incoming::create([
                    'daily_report_id' => $dailyReport->id,
                    'purchase_id' => $request->input('purchase_id'),
                    'vendor_id' => $request->input('vendor_id'),
                    'sopir' => $request->input('sopir'),
                    'no_polisi' => $request->input('no_polisi'),
                    'stik_sebelum_curah' => $request->input('stik_sebelum_curah'),
                    'stik_setelah_curah' => $request->input('stik_setelah_curah'),
                    'volume' => $request->input('penerimaan'),
                ]);
            }

            //check input pengeluaran not null
            if ($request->input('pengeluaran') != null && $request->input('pengeluaran') != 0) {
                $categories = $request->input('category_id');
                $jumlahs = $request->input('jumlah');
                $keterangans = $request->input('keterangan');

                foreach ($categories as $index => $category) {
                    Spending::create([
                        'shop_id' => $validated['shop_id'],
                        'daily_report_id' => $dailyReport->id,
                        'category_id' => $category,
                        'jumlah' => $jumlahs[$index],
                        'keterangan' => $keterangans[$index],
                        'created_at' => $validated['created_at'],
                    ]);
                }
            }


            $params = ['year_month' => $validated['created_at']->format('Y-m')];

            if (Auth::user()->role == 'super-admin') {
                $params = ['shop_id' => $validated['shop_id'], 'year_month' => $validated['created_at']->format('Y-m')];
            }

            DB::commit();

            return to_route('daily-reports.index', $params)->with('success', 'Data laporan harian berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollback();


            return redirect()->back()->with('error', 'Transaction failed: ' . $e->getMessage());
        }
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
        // dd($dailyReport->stik_akhir);
        $shops = Shop::all();
        $operators = User::where('role', 'operator')->where('shop_id', $dailyReport->shop_id)->get();

        // Retrieve purchases that belong to the same shop as the daily report and either do not have any incoming records or have an incoming record with the same purchase ID as the daily report's incoming record.
        $purchases = Purchase::where('shop_id', $dailyReport->shop_id)->get()->filter(function ($purchase) use ($dailyReport) {
            return $purchase->sisa > 0 || $purchase->id == $dailyReport->incoming?->purchase_id;
        });

        $categories = SpendingCategory::all();

        $vendors = Vendor::all();

        return view('daily_report.edit', compact('dailyReport', 'shops', 'operators', 'purchases', 'categories', 'vendors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DailyReport $dailyReport)
    {
        //validate request
        $customMessages = [
            'required' => ':attribute wajib diisi.',
        ];
        $validated = $request->validate([
            'operator_id' =>  [
                Rule::requiredIf(function () {
                    return Auth::user()->role == 'super-admin';
                }),
            ],
            'tanggal' => 'required|date',
            'jam' => 'required',
            'price_id' => 'required|numeric',
            'totalisator_awal' => 'required|numeric',
            'totalisator_akhir' => 'required|numeric',
            'stik_awal' => 'required|numeric',
            'stik_akhir' => 'nullable',
            'setor_tunai' => 'nullable',
            'setor_qris' => 'nullable',
            'setor_transfer' => 'nullable'
        ], $customMessages);

        $validated['created_at'] = Carbon::parse($validated['tanggal'] . ' ' . $validated['jam']);

        try {
            DB::beginTransaction();

            //create daily report
            $dailyReport->update($validated);


            $params = ['year_month' => $validated['created_at']->format('Y-m')];

            $params = (Auth::user()->role == 'super-admin')
                ? ['shop_id' => $dailyReport->shop_id, 'year_month' => $validated['created_at']->format('Y-m')]
                : null;

            //check input test_pump not null
            if ($request->input('test_pump') != null && $request->input('test_pump') != 0) {
                TestPump::updateOrCreate([
                    'daily_report_id' => $dailyReport->id,
                    'volume_test' => $request->input('volume_test'),
                    'volume_aktual' => $request->input('test_pump'),
                ]);
            } else {
                $dailyReport->testPump()->delete();
            }

            //check input penerimaan not null
            if ($request->input('penerimaan') != null && $request->input('penerimaan') != 0) {
                Incoming::updateOrCreate(['daily_report_id' => $dailyReport->id], [
                    'purchase_id' => $request->input('purchase_id'),
                    'vendor_id' => $request->input('vendor_id'),
                    'sopir' => $request->input('sopir'),
                    'volume' => $request->input('penerimaan'),
                    'no_polisi' => $request->input('no_polisi'),
                    'stik_sebelum_curah' => $request->input('stik_sebelum_curah'),
                    'stik_setelah_curah' => $request->input('stik_setelah_curah'),
                ]);
            } else {
                $dailyReport->incoming()->delete();
            }

            //check input pengeluaran not null
            if ($request->input('pengeluaran') != null && $request->input('pengeluaran') != 0) {
                $categories = $request->input('category_id');
                $jumlahs = $request->input('jumlah');
                $keterangans = $request->input('keterangan');

                $dailyReport->spendings()->delete();

                foreach ($categories as $index => $category) {
                    Spending::create([
                        'shop_id' => $dailyReport->shop_id,
                        'daily_report_id' => $dailyReport->id,
                        'category_id' => $category,
                        'jumlah' => $jumlahs[$index],
                        'keterangan' => $keterangans[$index],
                        'created_at' => $validated['created_at'],
                    ]);
                }
            } else {
                $dailyReport->spendings()->delete();
            }


            DB::commit();

            return to_route('daily-reports.index', $params)->with('success', 'Data laporan harian berhasil diubah.');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Transaction failed: ' . $e->getMessage());
        }
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

    public function getShopData(Request $request)
    {

        $shop_id = $request->input('shop_id');
        $operator_id = $request->input('operator_id');
        $tanggal = $request->input('tanggal');
        $jam = $request->input('jam');
        $date = Carbon::parse($tanggal . ' ' . $jam);

        //cek apakah hari ini ada pergantian harga
        $count_price = Price::whereDate('created_at', $date->format('Y-m-d'))->whereTime('created_at', '<=', $date->format('H:i:s'))->count();

        if ($count_price > 0) {
            $prices = Price::where('created_at', '<=', $date->format('Y-m-d H:i:s'))->latest()->limit(2)->get();
        } else {
            $prices = Price::where('created_at', '<=', $date->format('Y-m-d H:i:s'))->latest()->limit(1)->get();
        }

        $operators = [];
        $total_spendings = 0;
        $total_incoming = 0;
        $total_test_pumps = 0;
        $totalisator_awal = 0;
        $stik_awal = 0;
        $skala = 21;

        $today_penerimaan = 0;
        $today_penjualan = 0;

        if ($shop_id) {
            $operators = User::where('role', 'operator')->where('shop_id', $shop_id)->get();

            $shop = Shop::find($shop_id);

            $latest_report = DailyReport::where('shop_id', $shop->id)
                ->where('created_at', '<', $date->format('Y-m-d H:i:s'))
                ->latest()
                ->first();

            $skala = $shop->skala;
            $totalisator_awal =  $latest_report ? $latest_report->totalisator_akhir : $shop->totalisator_awal;
            $stik_awal =  $latest_report && $latest_report->stik_akhir ? $latest_report->stik_akhir  : $shop->stik_awal;
            $today_report = DailyReport::where('shop_id', $shop->id)->whereDate('created_at', $date->format('Y-m-d'))->get();
            $today_penjualan = $today_report->sum('volume_penjualan');
            $today_penerimaan = $today_report->sum('penerimaan');
        }

        return response()->json(compact('operators', 'totalisator_awal', 'stik_awal', 'skala', 'prices', 'today_penjualan', 'today_penerimaan'));
    }

    public function detail($shop_id, $date)
    {
        $reports = DailyReport::with(['operator', 'spendings.category', 'incoming', 'testPump'])->where('shop_id', $shop_id)->whereDate('created_at', $date)->oldest()->get();
        //redirect 404 if not found
        if ($reports->count() == 0) {
            abort(404);
        }
        return view('daily_report.detail', compact('reports'));
    }
}
