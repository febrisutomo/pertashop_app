<?php

namespace App\Http\Controllers;

use App\Models\Price;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Shop;
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
            $data = Sale::where('shop_id', $shop_id)->with(['operator.user', 'price'])->latest()->get();
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


        return view('sales.index', ['shops' => Shop::all()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $harga = Price::latest()->first();
        $penjualan_akhir = Sale::where('shop_id', 1)->latest()->first();
        $totalisator_awal = $penjualan_akhir ? $penjualan_akhir->totalisator_akhir : 205291.48;

        $stok_awal = 3000;
        $jumlah_pembelian = $stok_awal; //A

        $total_penjualan = Sale::where('shop_id', 1)->whereMonth('created_at', Carbon::now()->month)->get()->sum('jumlah');
        $test_pump = 0; //d
        $jumlah_penjualan = $total_penjualan - $test_pump;

        $sisa_stok = $jumlah_pembelian - $jumlah_penjualan;

        $stik_akhir = Shop::find(1)->stik_akhir;

        $data = [
            'totalisator_awal' => $totalisator_awal,
            'harga' => $harga->harga_jual,
            'jumlah_penjualan' => $jumlah_penjualan,
            'stik_akhir' =>  $stik_akhir,
            'sisa_stok' =>  $sisa_stok,
        ];

        return view('sales.create', $data);
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

        $validatedData = $request->validate([
            'created_at' => 'required|date',
            'totalisator_akhir' => 'required|numeric',
            'stik_akhir' => 'required|numeric',
            'jumlah' => 'required|numeric',
            'losses_gain' => 'required|numeric'
        ], $customMessages);

        $currentTime = Carbon::now();
        $validatedData['created_at'] = Carbon::createFromFormat('Y-m-d', $validatedData['created_at'])
            ->setTime($currentTime->hour, $currentTime->minute, 0);

        $validatedData['operator_id'] = Auth::user()->id;
        $validatedData['harga'] =  Price::latest()->first()->harga_jual;


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
    public function edit(Sale $sale)
    {
        $harga = Price::latest()->first();
        $penjualan_akhir = Sale::where('shop_id', 1)->where('created_at', '<', $sale->created_at)->latest()->first();

        $stok_awal = Shop::find(1)->stok_awal;
        $jumlah_pembelian = $stok_awal; //A

        $total_penjualan = Sale::where('shop_id', 1)->where('created_at', '<', $sale->created_at)->whereMonth('created_at', Carbon::now()->month)->get()->sum('jumlah');
        $test_pump = 0; //d
        $jumlah_penjualan = $total_penjualan - $test_pump;

        $sisa_stok = $jumlah_pembelian - $jumlah_penjualan;

        $stik_akhir = $penjualan_akhir ? $penjualan_akhir->stik_akhir : 142.85;

        $data = [
            'harga' => $harga->harga_jual,
            'jumlah_penjualan' => $jumlah_penjualan,
            'sisa_stok' =>  $sisa_stok,
            'sale' => $sale,
            'stik_akhir' => $stik_akhir
        ];
        return view('sales.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        $customMessages = [
            'required' => ':attribute wajib diisi.',
            'numeric' => ':attribute harus berupa angka.',
        ];

        $validatedData = $request->validate([
            'totalisator_akhir' => 'required|numeric',
            'stik_akhir' => 'required|numeric',
            'jumlah' => 'required|numeric',
            'losses_gain' => 'required|numeric'
        ], $customMessages);

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
