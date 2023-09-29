<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Document;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'file_dokumen' => 'required|mimes:pdf|max:2048',
            'nama_dokumen' => 'required',
            'izin_dikeluarkan' => 'nullable',
            'izin_berakhir' => 'nullable',
            'shop_id' => 'required',
        ]);

        if ($request->hasFile('file_dokumen')) {
            $directory = 'documents/';
            $file = $request->file('file_dokumen');
            $fileName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            if (File::exists($directory . $file->getClientOriginalName())) {
                $fileName = pathinfo($fileName, PATHINFO_FILENAME) . '-' . Str::random(3) . '.' . $extension;
            }
            $file->move(public_path($directory), $fileName);
            $validated['nama_file'] = $fileName;

            Document::create($validated);
        }

        return redirect()->back()->with('success', 'Dokumen berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Document $document)
    {
        $validated = $request->validate([
            'nama_dokumen' => 'required',
            'izin_dikeluarkan' => 'nullable',
            'izin_berakhir' => 'nullable',
        ]);

        $document->update($validated);

        return redirect()->back()->with('success', 'Data Dokumen berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {

        $document->delete();

        if (File::exists('documents/' . $document->nama_file)) {
            File::delete('documents/' . $document->nama_file);
        }

        return response()->json([
            'message' => 'Dokumen berhasil dihapus.',
        ]);
    }
}
