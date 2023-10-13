<?php

namespace App\Http\Controllers;

use App\Models\Price;
use Carbon\Carbon;
use App\Models\Shop;
use App\Models\Purchase;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
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

        $purchases = Purchase::with('vendor', 'incomings')->where('shop_id', $shop_id)->whereYear('created_at', $year)->whereMonth('created_at', $month)->get();

        $shops = Shop::all();

        return view('purchase.index', compact('shops', 'purchases'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $shops = Shop::all();
        $vendors = Vendor::all();

        return view('purchase.create', compact('vendors', 'shops'));
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
            'shop_id' =>   [
                Rule::requiredIf(function () {
                    return Auth::user()->role == 'super-admin';
                }),
            ],
            'vendor_id' => 'required|numeric',
            'no_so' => 'required|string|unique:purchases',
            'volume' => 'required|numeric',
            'total_bayar' => 'required|numeric',

        ], $customMessages);

        if (Auth::user()->role == 'admin') {
            $validatedData['shop_id'] = Auth::user()->shop_id;
        }

        $purchase = Purchase::create($validatedData);

        return to_route('purchases.index', ['shop_id' => $purchase->shop_id, 'year_month' => $purchase->created_at->format('Y-m')])->with('success', 'Data pembelian berhasil disimpan.');
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
        $vendors = Vendor::all();
        $shops = Shop::all();

        return view('purchase.edit', compact('vendors', 'shops', 'purchase'));
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
            'vendor_id' => 'required|numeric',
            'no_so' => 'required|string|unique:purchases,no_so,' . $purchase->id . ',id',
            'volume' => 'required|numeric',
            'total_bayar' => 'required|numeric',

        ], $customMessages);

        $purchase->update($validatedData);

        return to_route('purchases.index', ['shop_id' => $purchase->shop_id, 'year_month' => $purchase->created_at->format('Y-m')])->with('success', 'Data pembelian berhasil diubah.');
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
