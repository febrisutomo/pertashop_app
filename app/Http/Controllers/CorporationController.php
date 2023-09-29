<?php

namespace App\Http\Controllers;

use App\Models\Corporation;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CorporationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Corporation::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $button = '<a href="' . route('corporations.edit', $row->id) . '" class="btn btn-sm btn-info" title="Edit"><i class="fa fa-edit"></i></a>';
                    $button .= ' <button class="btn btn-sm btn-danger btn-delete" title="hapus" data-id="' . $row->id . '"><i class="fa fa-trash"></i></button>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }


        return view('corporation.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('corporation.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // dd($request->all());
        $validated = $request->validate(
            [
                'nama' => 'required',
                'no_hp' => 'nullable',
                'alamat' => 'required',
                'no_rekening' => 'nullable',
                'pemilik_rekening' => 'nullable',
                'nama_bank' => 'nullable',

            ]
        );

        Corporation::create($validated);

        return redirect()->route('corporations.index')->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Corporation $corporation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Corporation $corporation)
    {
        return view('corporation.edit', compact('corporation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Corporation $corporation)
    {
        // dd($request->all());
        $validated = $request->validate(
            [
                'nama' => 'required',
                'no_hp' => 'nullable',
                'alamat' => 'required',
                'no_rekening' => 'nullable',
                'pemilik_rekening' => 'nullable',
                'nama_bank' => 'nullable',
                'izin_dikeluarkan' => 'nullable',
                'izin_berakhir' => 'nullable',
                'documents' => 'nullable',
            ]
        );


        $corporation->update($validated);

        return redirect()->route('corporations.index')->with('success', 'Data berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Corporation $corporation)
    {
        $corporation->delete();

        return response()->json([
            'message' => 'Data berhasil dihapus.',
        ]);
    }
}
