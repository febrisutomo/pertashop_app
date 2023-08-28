<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Spending;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;

class SpendingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $shop_id = $request->input('shop_id', 1);

            $spendingsByMonth = Spending::with(['shop'])
                ->where('shop_id', $shop_id)
                ->latest()
                ->get()
                ->groupBy(function ($item) {
                    return $item->created_at->format('Y-m');
                });



            $monthsToDisplay = [];

            // Create the array of months
            $firstData = Spending::with(['shop'])
                ->where('shop_id', $shop_id)
                ->oldest()
                ->get()->first();
            $startDate = $firstData ? $firstData->created_at : Carbon::now(); // Start from oldest data
            $endDate = now();

            while ($startDate <= $endDate) {
                $monthsToDisplay[$startDate->format('Y-m')] = 0;
                $startDate->addMonth();
            }

            $data = [];

            foreach ($spendingsByMonth as $yearMonth => $spendings) {
                $totalAmount = $spendings->sum('jumlah');
                $monthsToDisplay[$yearMonth] = $totalAmount;
            }

            // Now $monthsToDisplay contains all months from January 2021 with their respective totals (0 or actual total)
            foreach ($monthsToDisplay as $yearMonth => $total) {
                $data[] = [
                    'bulan' => $yearMonth,
                    'total' => $total,
                    'shop_id' => $shop_id,
                ];
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $button = '<a href="' . route('spendings.edit', ['shop_id' => $row['shop_id'], 'year_month' => $row['bulan']]) . '" class="btn btn-sm btn-info" title="Edit"><i class="fa fa-edit"></i></a>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }


        $shops = Shop::all();
        return view('spending.index', compact('shops'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Spending $spending)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $shop_id, string $year_month)
    {
        if ($request->ajax()) {
            list($year, $month) = explode("-", $year_month);
            $spendings = Spending::with(['shop'])
                ->where('shop_id', $shop_id)
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->get();

            return DataTables::of($spendings)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $button = '<button class="btn btn-sm btn-info" title="Edit"><i class="fa fa-edit"></i></button>';
                    $button .= ' <button class="btn btn-sm btn-danger btn-delete" title="hapus" data-id="' . $row['bulan'] . '"><i class="fa fa-trash"></i></button>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $shop = Shop::find($shop_id);

        $periode = Carbon::createFromFormat('Y-m', $year_month);

        return view('spending.edit', compact('periode', 'year_month', 'shop'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Spending $spending)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Spending $spending)
    {
        //
    }
}
