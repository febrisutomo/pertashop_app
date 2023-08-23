<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Operator;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OperatorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $shop_id = $request->input('shop_id');

            $data = Operator::with(['user', 'shop'])->latest()->get();

            if ($shop_id) {
                $data = Operator::with(['user', 'shop'])->where('shop_id', $shop_id)->latest()->get();
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $button = '<a href="' . route('operators.edit', $row->id) . '" class="btn btn-sm btn-info" title="Edit"><i class="fa fa-edit"></i></a>';
                    $button .= ' <button class="btn btn-sm btn-danger btn-delete" title="hapus" data-id="' . $row->id . '"><i class="fa fa-trash"></i></button>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $shops = Shop::all();
        return view('operator.index', compact('shops'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $shops = Shop::all();

        return view('operator.create', compact('shops'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Operator $operator)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Operator $operator)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Operator $operator)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Operator $operator)
    {
        //
    }

    public function getOperatorByShop(string $shop_id)
    {
        $operators = Operator::where('shop_id', $shop_id)->get();

        return response()->json(['operator' => $operators]);
    }
}
