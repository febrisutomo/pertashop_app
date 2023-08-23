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
            $shop_id = $request->input('shop_id', 1);

            if (Auth::user()->role != 'operator') {
                $data = Incoming::with(['purchase.supplier', 'operator.user'])->where('shop_id', $shop_id)->latest()->get();
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $button = '<a href="' . route('incomings.edit', $row->id) . '" class="btn btn-sm btn-info" title="Edit"><i class="fa fa-edit"></i></a>';
                        $button .= ' <button class="btn btn-sm btn-danger btn-delete" title="hapus" data-id="' . $row->id . '"><i class="fa fa-trash"></i></button>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }

            $shop_id = Auth::user()->operator->shop->id;
            $data = Incoming::with(['purchase.supplier', 'operator.user'])->where('shop_id', $shop_id)->latest()->get();
            return Datatables::of($data)
                ->addColumn('action', function ($row) use ($data) {
                    $lastRow = $data->first(); // Mendapatkan data terakhir dari koleksi
                    $button = '';

                    if ($row->id === $lastRow->id && $row->operator_id === Auth::user()->operator->id) { // Menambahkan tombol hanya pada data terakhir
                        $button = '<a href="' . route('incomings.edit', $row->id) . '" class="btn btn-sm btn-info" title="Edit"><i class="fa fa-edit"></i></a>';
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
        if ($request->ajax()) {
            $shop_id = $request->input('shop_id');
            $operators = Operator::with('user')->where('shop_id', $shop_id)->get();
            $purchases = Purchase::with('supplier')->where('shop_id', $shop_id)->get()->where('sisa', '>', 0);
            return response()->json(compact('purchases', 'operators'));
        }

        $operators = Operator::all();
        $shops = Shop::all();


        return view('incoming.create', compact('operators', 'shops'));
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
                'purchase_id' => 'required|numeric',
                'jumlah' => 'required|numeric',
                'stik_awal' => 'required|numeric',
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
                'purchase_id' => 'required|numeric',
                'jumlah' => 'required|numeric',
                'stik_awal' => 'required|numeric',
                'stik_akhir' => 'required|numeric',
            ], $customMessages);
            $validatedData['created_at'] = $validatedData['date'] . ' ' . $validatedData['time'];
        }


        Incoming::create($validatedData);

        return to_route('incomings.index')->with('success', 'Data kedatangan telah berhasil disimpan.');
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
        if ($request->ajax()) {
            $shop_id = $request->input('shop_id');
            $operators = Operator::with('user')->where('shop_id', $shop_id)->get();
            $purchases = Purchase::with('supplier')->where('shop_id', $shop_id)->get()->filter(function ($value, int $key) use ($incoming) {
                return $value->sisa > 0 || $value->id == $incoming->purchase_id;
            });
            return response()->json(compact('purchases', 'operators'));
        }

        $operators = Operator::all();
        $shops = Shop::all();


        return view('incoming.edit', compact('operators', 'shops', 'incoming'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Incoming $incoming)
    {
        $customMessages = [
            'required' => ':attribute wajib diisi.',
            'numeric' => ':attribute harus berupa angka.',
            'date' => ':attribute harus berupa tanggal.',
        ];

        if (Auth::user()->role === 'operator') {
            $validatedData = $request->validate([
                'purchase_id' => 'required|numeric',
                'jumlah' => 'required|numeric',
                'stik_awal' => 'required|numeric',
                'stik_akhir' => 'required|numeric',
            ], $customMessages);
        } else {
            $validatedData = $request->validate([
                'date' => 'required|date',
                'time' => 'required|string',
                'operator_id' => 'required|numeric',
                'shop_id' => 'required|numeric',
                'purchase_id' => 'required|numeric',
                'jumlah' => 'required|numeric',
                'stik_awal' => 'required|numeric',
                'stik_akhir' => 'required|numeric',
            ], $customMessages);
            $validatedData['created_at'] = $validatedData['date'] . ' ' . $validatedData['time'];
        }


        $incoming->update($validatedData);

        return to_route('incomings.index')->with('success', 'Data kedatangan telah berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Incoming $incoming)
    {
        $incoming->delete();

        return response()->json([
            'message' => 'Data kedatangan telah dihapus.'
        ]);
    }
}
