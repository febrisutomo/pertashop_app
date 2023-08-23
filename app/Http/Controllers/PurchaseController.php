<?php

namespace App\Http\Controllers;

use App\Models\Price;
use App\Models\Purchase;
use App\Models\Shop;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $shop_id = $request->input('shop_id', 1);

            $data = Purchase::where('shop_id', $shop_id)->with(['supplier', 'price'])->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $button = '<a href="' . route('purchases.edit', $row->id) . '" class="btn btn-sm btn-info" title="Edit"><i class="fa fa-edit"></i></a>';
                    $button .= ' <button class="btn btn-sm btn-danger btn-delete" title="hapus" data-id="' . $row->id . '"><i class="fa fa-trash"></i></button>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }


        $shops = Shop::all();
        return view('purchase.index', compact('shops'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $harga = Price::latest()->first()->harga_beli;
        $suppliers = Supplier::all();
        $shops = Shop::all();

        return view('purchase.create', compact('harga', 'suppliers', 'shops'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $customMessages = [
            'required' => ':attribute wajib diisi.',
        ];

        $validatedData = $request->validate([
            'created_at' => 'required|date',
            'shop_id' => 'required|numeric',
            'supplier_id' => 'required|numeric',
            'jumlah' => 'required|numeric',

        ], $customMessages);


        $validatedData['price_id'] =  Price::latest()->first()->id;


        Purchase::create($validatedData);

        return to_route('purchases.index')->with('success', 'Data pembelian telah berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {
        $harga = $purchase->price->harga_beli;
        $suppliers = Supplier::all();
        $shops = Shop::all();
        return view('purchase.edit', compact('harga', 'suppliers', 'shops', 'purchase'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Purchase $purchase)
    {
        $customMessages = [
            'required' => ':attribute wajib diisi.',
        ];

        $validatedData = $request->validate([
            'created_at' => 'required|date',
            'shop_id' => 'required|numeric',
            'supplier_id' => 'required|numeric',
            'jumlah' => 'required|numeric',

        ], $customMessages);

        $purchase->update($validatedData);

        return to_route('purchases.index')->with('success', 'Data pembelian telah berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        $purchase->delete();

        return response()->json([
            'message' => 'Data pembelian telah dihapus.'
        ]);
    }
}
