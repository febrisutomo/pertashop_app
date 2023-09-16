<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\User;
use App\Models\Investor;
use App\Models\Corporation;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Shop::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $button = '<a href="' . route('shops.edit', $row->id) . '" class="btn btn-sm btn-info mr-1" title="Edit"><i class="fa fa-edit"></i></a>';
                    $button .= '<a href="' . route('shops.investors', $row->id) . '" class="btn btn-sm btn-success mr-1" title="Investors"><i class="fa fa-users"></i></a>';
                    $button .= '<button class="btn btn-sm btn-danger btn-delete" title="hapus" data-id="' . $row->id . '"><i class="fa fa-trash"></i></button>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }


        return view('shop.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $corporations = Corporation::all();
        return view('shop.create', compact('corporations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required',
            'kode' => 'required',
            'alamat' => 'required',
            'stik_awal' => 'required',
            'totalisator_awal' => 'required',
            'corporation_id' => 'required',
            'modal_awal' => 'required',
            'kapasitas' => 'required',
            'skala' => 'required',
        ]);

        Shop::create($validated);

        return redirect()->route('shops.index')->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Shop $shop)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shop $shop)
    {
        $corporations = Corporation::all();
        return view('shop.edit', compact('shop', 'corporations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shop $shop)
    {
        $validated = $request->validate([
            'nama' => 'required',
            'kode' => 'required',
            'alamat' => 'required',
            'stik_awal' => 'required',
            'totalisator_awal' => 'required',
            'corporation_id' => 'required',
            'modal_awal' => 'required',
            'kapasitas' => 'required',
            'skala' => 'required',
        ]);

        $shop->update($validated);

        return redirect()->route('shops.index')->with('success', 'Data berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shop $shop)
    {
        $shop->delete();
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }

    public function investor(Request $request, Shop $shop)
    {
        // $investors = User::where('role', 'investor')->whereDoesntHave('investments', function ($query) use ($shop) {
        //     $query->where('shop_id', $shop->id);
        // })->get();
        $investors = User::where('role', 'investor')->get();

        return view('shop.investors', compact('shop', 'investors'));
    }

    public function investorStore(Request $request, Shop $shop)
    {
        $request->validate([
            'investor_id' => 'required',
            'persentase' => 'required',
            'nama_bank' => 'required',
            'no_rekening' => 'required',
            'pemilik_rekening' => 'required',
        ]);

        //attach investors to shop


        $shop->investors()->attach([
            $request->investor_id =>
            [
                'persentase' => $request->persentase,
                'nama_bank' => $request->nama_bank,
                'no_rekening' => $request->no_rekening,
                'pemilik_rekening' => $request->pemilik_rekening
            ]
        ]);

        return redirect()->back()->with('success', 'Investor berhasil ditambahkan ke Pertashop.');
    }

    public function investorUpdate(Request $request, Shop $shop)
    {
        $request->validate([
            'investor_id' => 'required',
            'persentase' => 'required',
            'nama_bank' => 'required',
            'no_rekening' => 'required',
            'pemilik_rekening' => 'required',
        ]);

        $shop->investors()->detach($request->investor_id);

        $shop->investors()->attach([
            $request->investor_id =>
            [
                'persentase' => $request->persentase,
                'nama_bank' => $request->nama_bank,
                'no_rekening' => $request->no_rekening,
                'pemilik_rekening' => $request->pemilik_rekening
            ]
        ]);

        return redirect()->back()->with('success', 'Investor berhasil berhasil diupdate.');
    }

    public function investorDestroy(Request $request, Shop $shop)
    {

        $shop->investors()->detach($request->id);

        return response()->json(['message' => 'Investor berhasil dihapus dari Pertashop.']);
    }
}
