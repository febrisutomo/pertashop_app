<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Incoming;
use App\Models\Operator;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class IncomingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            if (Auth::user()->role == 'admin') {
                $shop_id = Auth::user()->admin->shop_id;
            } elseif (Auth::user()->role == 'operator') {
                $shop_id = Auth::user()->operator->shop_id;
            } else {
                $shop_id = $request->input('shop_id', 1);
            }

            $data = Incoming::with(['purchase.supplier', 'operator.user'])->where('shop_id', $shop_id)->latest()->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($data) {
                    if (Auth::user()->role == 'operator') {
                        $lastRow = $data->first(); // Mendapatkan data terakhir dari koleksi
                        $button = '';

                        if ($row->id === $lastRow->id && $row->operator_id === Auth::user()->operator->id) { // Menambahkan tombol hanya pada data terakhir
                            $button = '<a href="' . route('incomings.edit', $row->id) . '" class="btn btn-sm btn-info" title="Edit"><i class="fa fa-edit"></i></a>';
                            $button .= ' <button class="btn btn-sm btn-danger btn-delete" title="hapus" data-id="' . $row->id . '"><i class="fa fa-trash"></i></button>';
                        }
                    } else {
                        $button = '<a href="' . route('incomings.edit', $row->id) . '" class="btn btn-sm btn-info" title="Edit"><i class="fa fa-edit"></i></a>';
                        $button .= ' <button class="btn btn-sm btn-danger btn-delete" title="hapus" data-id="' . $row->id . '"><i class="fa fa-trash"></i></button>';
                    }

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }


        $shops = Shop::all();
        return view('incoming.index', compact('shops'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $operator = Auth::user()->operator;
        $shop = $operator->shop;
        $purchases = Purchase::with('supplier')
            ->where('shop_id', $shop->id)
            ->get()->where('sisa', '>', 0);
        $incoming = Incoming::where('operator_id', $operator->id)
            ->whereDate('created_at', Carbon::now()->format('Y-m-d'))->first();
        return view('incoming.create', compact('purchases', 'shop'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $customMessages = [
            'required' => ':attribute wajib diisi.',
        ];
        $validated = $request->validate([
            'purchase_id' => 'required|numeric',
            'sopir' => 'required|string',
            'no_polisi' => 'required|string',
            'volume' => 'required|numeric',
            'stik_awal' => 'required|numeric',
            'stik_akhir' => 'required|numeric',
        ], $customMessages);

        $validated['created_at'] = Carbon::now()->format('Y-m-d H:i');
        $validated['shop_id'] = Auth::user()->operator->shop_id;
        $validated['operator_id'] = Auth::user()->operator->id;

        Incoming::create($validated);

        return to_route('incomings.index')->with('success', 'Data penerimaan berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Incoming $incoming)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Incoming $incoming)
    {
        $operator = $incoming->operator;
        $shop = $operator->shop;
        $purchases = Purchase::with('supplier')->where('shop_id', $shop->id)->get()->filter(function ($value, int $key) use ($incoming) {
            return $value->sisa > 0 || $value->id == $incoming->purchase_id;
        });
        $incoming = Incoming::where('operator_id', $operator->id)
            ->whereDate('created_at', Carbon::now()->format('Y-m-d'))->first();
        return view('incoming.edit', compact('purchases', 'shop', 'incoming'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Incoming $incoming)
    {
        $customMessages = [
            'required' => ':attribute wajib diisi.',
        ];
        $validated = $request->validate([
            'purchase_id' => 'required|numeric',
            'sopir' => 'required|string',
            'no_polisi' => 'required|string',
            'volume' => 'required|numeric',
            'stik_awal' => 'required|numeric',
            'stik_akhir' => 'required|numeric',
        ], $customMessages);

        $incoming->update($validated);

        return to_route('incomings.index')->with('success', 'Data penerimaan berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Incoming $incoming)
    {
        $incoming->delete();

        return response()->json([
            'message' => 'Data penerimaan telah dihapus.'
        ]);
    }
}
