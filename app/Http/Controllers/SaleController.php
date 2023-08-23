<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Shop;
use App\Models\Price;
use App\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $shop_id = $request->input('shop_id', 1);

            if (Auth::user()->role != 'operator') {
                $data = Sale::with(['operator.user', 'price'])->where('shop_id', $shop_id)->latest()->get();
                return Datatables::of($data)
                    // ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $button = '<a href="' . route('sales.edit', $row->id) . '" class="btn btn-sm btn-info" title="Edit"><i class="fa fa-edit"></i></a>';
                        $button .= ' <button class="btn btn-sm btn-danger btn-delete" title="hapus" data-id="' . $row->id . '"><i class="fa fa-trash"></i></button>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }

            $shop_id = Auth::user()->operator->shop->id;
            $data = Sale::with(['operator.user', 'price'])->where('shop_id', $shop_id)->latest()->get();
            return Datatables::of($data)
                ->addColumn('action', function ($row) use ($data) {
                    $lastRow = $data->first(); // Mendapatkan data terakhir dari koleksi
                    $button = '';

                    if ($row->id === $lastRow->id && $row->operator_id === Auth::user()->operator->id) { // Menambahkan tombol hanya pada data terakhir
                        $button = '<a href="' . route('sales.edit', $row->id) . '" class="btn btn-sm btn-info" title="Edit"><i class="fa fa-edit"></i></a>';
                    }

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }


        return view('sales.index', ['shops' => Shop::all()]);
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

        $harga = Price::latest()->first()->harga_jual;
        $shops = Shop::all();

        return view('sales.create', compact('harga', 'shops'));
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
                'stik_akhir' => 'required|numeric',
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
                'stik_akhir' => 'required|numeric',
            ], $customMessages);
            $validatedData['created_at'] = $validatedData['date'] . ' ' . $validatedData['time'];
        }


        $validatedData['price_id'] =  Price::latest()->first()->id;

        Sale::create($validatedData);

        return to_route('sales.index')->with('success', 'Data penjualan telah berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Sale $sale)
    {
        if ($request->ajax()) {
            $shop_id = $request->input('shop_id');
            $operators = Operator::with('user')->where('shop_id', $shop_id)->get();
            $totalisator_awal = $sale->totalisator_awal;

            return response()->json(compact('totalisator_awal', 'operators'));
        }

        $harga = Price::latest()->first()->harga_jual;
        $shops = Shop::all();

        return view('sales.edit', compact('sale', 'harga', 'shops'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
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
                'stik_akhir' => 'required|numeric',
            ], $customMessages);

        } else {
            $validatedData = $request->validate([
                'date' => 'required|date',
                'time' => 'required|string',
                'operator_id' => 'required|numeric',
                'shop_id' => 'required|numeric',
                'totalisator_awal' => 'required|numeric',
                'totalisator_akhir' => 'required|numeric',
                'stik_akhir' => 'required|numeric',
            ], $customMessages);

            $validatedData['created_at'] = $validatedData['date'] . ' ' . $validatedData['time'];
        }


        $sale->update($validatedData);

        return to_route('sales.index')->with('success', 'Data penjualan telah berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        $sale->delete();

        return response()->json([
            'message' => 'Data penjualan telah dihapus.'
        ]);
    }
}
