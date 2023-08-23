<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Shop;
use App\Models\Operator;
use App\Models\TestPump;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class TestPumpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $shop_id = $request->input('shop_id', 1);

            if (Auth::user()->role != 'operator') {
                $data = TestPump::with(['shop', 'operator.user'])->where('shop_id', $shop_id)->latest()->get();
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $button = '<a href="' . route('test-pumps.edit', $row->id) . '" class="btn btn-sm btn-info" title="Edit"><i class="fa fa-edit"></i></a>';
                        $button .= ' <button class="btn btn-sm btn-danger btn-delete" title="hapus" data-id="' . $row->id . '"><i class="fa fa-trash"></i></button>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }

            $shop_id = Auth::user()->operator->shop->id;
            $data = TestPump::with(['shop', 'operator.user'])->where('shop_id', $shop_id)->latest()->get();
            return Datatables::of($data)
                ->addColumn('action', function ($row) use ($data) {
                    $lastRow = $data->first(); // Mendapatkan data terakhir dari koleksi
                    $button = '';

                    if ($row->id === $lastRow->id && $row->operator_id === Auth::user()->operator->id) { // Menambahkan tombol hanya pada data terakhir
                        $button = '<a href="' . route('test-pumps.edit', $row->id) . '" class="btn btn-sm btn-info" title="Edit"><i class="fa fa-edit"></i></a>';
                    }

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }


        $shops = Shop::all();
        return view('test-pump.index', compact('shops'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->ajax()) {
            $shop_id = $request->input('shop_id');
            $operators = Operator::with('user')->where('shop_id', $shop_id)->get();
            $totalisator_awal = ReportController::calcLabaKotor($shop_id)->last() ? ReportController::calcLabaKotor($shop_id)->last()['totalisator_akhir'] : Shop::find($shop_id)->totalisator_awal;

            return response()->json(compact('totalisator_awal', 'operators'));
        }

        $operators = Operator::all();
        $shops = Shop::all();

        return view('test-pump.create', compact('operators', 'shops'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $customMessages = [
            'required' => ':attribute wajib diisi.',
            'numeric' => ':attribute harus berupa angka.',
            'date' => ':attribute harus berupa tanggal.',
        ];

        if (Auth::user()->role === 'operator') {
            $validatedData = $request->validate([
                'totalisator_awal' => 'required|numeric',
                'totalisator_akhir' => 'required|numeric',
            ], $customMessages);

            $validatedData['operator_id'] = Auth::user()->operator->id;
            $validatedData['shop_id'] = Auth::user()->operator->shop->id;
            $validatedData['created_at'] = Carbon::now()->format('Y-m-d H:i:s');
        } else {
            $validatedData = $request->validate([
                'date' => 'required|date',
                'time' => 'required|string',
                'operator_id' => 'required|numeric',
                'shop_id' => 'required|numeric',
                'totalisator_awal' => 'required|numeric',
                'totalisator_akhir' => 'required|numeric',
            ], $customMessages);

            $validatedData['created_at'] = $validatedData['date'] . ' ' . $validatedData['time'];
        }

        TestPump::create($validatedData);

        return to_route('test-pumps.index')->with('success', 'Data percobaan telah berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TestPump $testPump)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, TestPump $testPump)
    {
        if ($request->ajax()) {
            $shop_id = $request->input('shop_id');
            $operators = Operator::with('user')->where('shop_id', $shop_id)->get();
            $totalisator_awal = $testPump->totalisator_awal;

            return response()->json(compact('totalisator_awal', 'operators'));
        }

        $operators = Operator::all();
        $shops = Shop::all();

        return view('test-pump.edit', compact('operators', 'shops', 'testPump'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TestPump $testPump)
    {
        $customMessages = [
            'required' => ':attribute wajib diisi.',
            'numeric' => ':attribute harus berupa angka.',
            'date' => ':attribute harus berupa tanggal.',
        ];

        if (Auth::user()->role === 'operator') {
            $validatedData = $request->validate([
                'totalisator_awal' => 'required|numeric',
                'totalisator_akhir' => 'required|numeric',
            ], $customMessages);
        } else {
            $validatedData = $request->validate([
                'date' => 'required|date',
                'time' => 'required|string',
                'operator_id' => 'required|numeric',
                'shop_id' => 'required|numeric',
                'totalisator_awal' => 'required|numeric',
                'totalisator_akhir' => 'required|numeric',
            ], $customMessages);

            $validatedData['created_at'] = $validatedData['date'] . ' ' . $validatedData['time'];
        }

        $testPump->update($validatedData);

        return to_route('test-pumps.index')->with('success', 'Data percobaan telah berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TestPump $testPump)
    {
        $testPump->delete();

        return response()->json([
            'message' => 'Data percobaan telah dihapus.'
        ]);
    }
}
