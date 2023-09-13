<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            if (Auth::user()->shop) {
                $shop_id = Auth::user()->shop_id;
                $users = User::with(['shop'])->where('shop_id', $shop_id)->latest()->get();
            } else {
                $shop_id = $request->input('shop_id');
                if ($shop_id) {
                    $users = User::with(['shop'])->where('shop_id', $shop_id)->latest()->get();
                } else {
                    $users = User::with(['shop'])->latest()->get();
                }
            }

            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $button = '<a href="' . route('users.edit', $row->id) . '" class="btn btn-sm btn-info" title="Edit"><i class="fa fa-edit"></i></a>';
                    $button .= ' <button class="btn btn-sm btn-danger btn-delete" title="hapus" data-id="' . $row->id . '"><i class="fa fa-trash"></i></button>';

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $shops = Shop::all();

        return view('users.index', compact('shops'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $shops = Shop::all();

        return view('users.create', compact('shops'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,operator,investor',
            'shop_id' => 'required_if:role,!=,investor',
            'alamat' => 'nullable',
            'no_hp' => 'nullable',
            'no_rekening' => 'nullable',
            'nama_bank' => 'nullable',
            'pemilik_rekening' => 'nullable',
        ]);

        if ($request->role == 'investor') {
            $validated['shop_id'] = null;
        }
        $validated['password'] = Hash::make('123');
        User::create($validated);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $shops = Shop::all();

        return view('users.edit', compact('shops', 'user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,operator,investor',
            'shop_id' => 'required_if:role,!=,investor',
            'alamat' => 'nullable',
            'no_hp' => 'nullable',
            'no_rekening' => 'nullable',
            'nama_bank' => 'nullable',
            'pasasword' => 'nullable',
            'pemilik_rekening' => 'nullable',
        ]);

        if ($request->role == 'investor') {
            $validated['shop_id'] = null;
        }

        $validated['password'] = Hash::make('123');
        $user->update($validated);

        if ($request->password) {
            $user->user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('users.index')->with('success', 'User berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json([
            'message' => 'User berhasil dihapus.',
        ]);
    }
}
