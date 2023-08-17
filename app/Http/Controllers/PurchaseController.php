<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Purchase::with('supplier')->latest()->get();
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


        return view('purchase.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'harga' => Product::latest()->first()->harga_beli,
            'suppliers' => Supplier::orderBy('nama')->get(),
        ];

        return view('purchase.create', $data);
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
            'jumlah' => 'required|numeric',

        ], $customMessages);

        $currentTime = Carbon::now();
        $validatedData['created_at'] = Carbon::createFromFormat('Y-m-d', $validatedData['created_at'])
            ->setTime($currentTime->hour, $currentTime->minute, 0);

        $validatedData['harga'] =  Product::latest()->first()->harga_beli;


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
        $data = [
            'purchase' => $purchase,
            'suppliers' => Supplier::orderBy('nama')->get(),
        ];

        return view('purchase.edit', $data);
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
            'jumlah' => 'required|numeric',

        ], $customMessages);

        $currentTime = Carbon::now();
        $validatedData['created_at'] = Carbon::createFromFormat('Y-m-d', $validatedData['created_at'])
            ->setTime($currentTime->hour, $currentTime->minute, 0);

        $validatedData['harga'] =  Product::latest()->first()->harga_beli;


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
