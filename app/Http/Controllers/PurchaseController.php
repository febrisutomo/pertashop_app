<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if (Auth::user()->role == 'admin') {
                $shop_id = Auth::user()->admin->shop_id;
            } else {
                $shop_id = $request->input('shop_id', 1);
            }

            $data = Purchase::where('shop_id', $shop_id)->with(['supplier'])->latest()->get();
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
        $suppliers = Supplier::all();
        $shops = Shop::all();

        return view('purchase.create', compact('suppliers', 'shops'));
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
            'supplier_id' => 'required|numeric',
            'no_so' => 'required|string',
            'volume' => 'required|numeric',
            'total_bayar' => 'required|numeric',

        ], $customMessages);

        $validatedData['shop_id'] = Auth::user()->admin->shop->id;

        Purchase::create($validatedData);

        return to_route('purchases.index')->with('success', 'Data pembelian berhasil disimpan.');
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
        $suppliers = Supplier::all();
        $shops = Shop::all();
        return view('purchase.edit', compact('suppliers', 'shops', 'purchase'));
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
            'supplier_id' => 'required|numeric',
            'no_so' => 'required|string',
            'volume' => 'required|numeric',
            'total_bayar' => 'required|numeric',

        ], $customMessages);

        $purchase->update($validatedData);

        return to_route('purchases.index')->with('success', 'Data pembelian berhasil diubah.');
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
