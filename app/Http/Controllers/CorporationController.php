<?php

namespace App\Http\Controllers;

use App\Models\Corporation;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Return_;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
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
        $valiadated = $request->validate(
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

        try {
            DB::beginTransaction();


            $corporation = Corporation::create($valiadated);

            if ($request->hasFile('documents')) {

                $documents = [];
                $directory = 'documents/';

                foreach ($request->file('documents') as $file) {
                    $fileName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();

                    if (File::exists($directory . $file->getClientOriginalName())) {
                        $fileName = pathinfo($fileName, PATHINFO_FILENAME) . '-' . Str::random(3) . '.' . $extension;
                    }

                    $file->move(public_path($directory), $fileName);

                    $documents[] = ['nama_file' => $fileName];
                }

                $corporation->documents()->createMany($documents);
            }


            DB::commit();

            return redirect()->route('corporations.index')->with('success', 'Data berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Transaction failed: ' . $e->getMessage());
        }
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
        $valiadated = $request->validate(
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

        try {
            DB::beginTransaction();


            $corporation->update($valiadated);

            if ($request->hasFile('documents')) {

                $documents = [];
                $directory = 'documents/';

                foreach ($request->file('documents') as $file) {
                    $fileName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();

                    if (File::exists($directory . $file->getClientOriginalName())) {
                        $fileName = pathinfo($fileName, PATHINFO_FILENAME) . '-' . Str::random(3) . '.' . $extension;
                    }

                    $file->move(public_path($directory), $fileName);

                    $documents[] = ['nama_file' => $fileName];
                }

                $corporation->documents()->createMany($documents);
            }


            DB::commit();

            return redirect()->route('corporations.index')->with('success', 'Data berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Transaction failed: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Corporation $corporation)
    {
        $corporation->delete();

        foreach ($corporation->documents as $document) {
            if (File::exists('documents/' . $document->nama_file)) {
                File::delete('documents/' . $document->nama_file);
            }
        }
        
        return response()->json([
            'message' => 'Data berhasil dihapus.',
        ]);
    }
}
