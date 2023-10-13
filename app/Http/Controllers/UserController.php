<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
        $users = User::with(['shop'])->whereNot('role', 'super-admin');

        $role = $request->input('role');

        if (Auth::user()->shop) {
            $shop_id = Auth::user()->shop_id;
            if ($role) {
                $users = $users->where('role', $role)->where('shop_id', $shop_id)->latest()->get();
            } else {
                $users = $users->where('shop_id', $shop_id)->orWhereRelation('investments', 'shop_id', $shop_id)->latest()->get();
            }
        } else {
            $shop_id = $request->input('shop_id');
            if ($role && $shop_id) {
                $users = $users->where('role', $role)->where('shop_id', $shop_id)->orWhereRelation('investments', 'shop_id', $shop_id)->latest()->get();
            } elseif ($role) {
                $users = $users->where('role', $role)->latest()->get();
            } elseif ($shop_id) {
                $users = $users->where('shop_id', $shop_id)->orWhereRelation('investments', 'shop_id', $shop_id)->latest()->get();
            } else {
                $users = $users->latest()->get();
            }
        }

        $shops = Shop::all();

        return view('users.index', compact('shops', 'users'));
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
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,operator,investor',
            'shop_id' =>   [
                Rule::requiredIf(function () use ($request) {
                    return $request->input('role') == 'admin' || $request->input('role') == 'operator';
                }),
            ],
            'alamat' => 'nullable',
            'no_hp' => 'nullable',
            'no_rekening' => 'nullable',
            'nama_bank' => 'nullable',
            'pemilik_rekening' => 'nullable',
            'password' => 'required',
        ]);


        if ($request->role == 'investor') {
            $validated['shop_id'] = null;
        }
        $validated['password'] = Hash::make($request->password);
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
            'shop_id' =>   [
                Rule::requiredIf(function () use ($request) {
                    return $request->input('role') == 'admin' || $request->input('role') == 'operator';
                }),
            ],
            'alamat' => 'nullable',
            'no_hp' => 'nullable',
            'no_rekening' => 'nullable',
            'nama_bank' => 'nullable',
            'pemilik_rekening' => 'nullable',
            'tabungan_awal' => 'nullable|numeric',
        ]);

        if ($request->role == 'investor') {
            $validated['shop_id'] = null;
        }

        if ($request->password) {
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

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

    public function profile()
    {
        $user = User::find(Auth::user()->id);

        return view('users.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . Auth::user()->id,
            'alamat' => 'nullable',
            'no_hp' => 'nullable',
            'no_rekening' => 'nullable',
            'nama_bank' => 'nullable',
            'pemilik_rekening' => 'nullable',
            'tabungan_awal' => 'nullable|numeric',
        ]);

        if ($request->password) {
            $validated['password'] = Hash::make($request->password);
        }

        $user = User::find(Auth::user()->id);

        $user->update($validated);

        return redirect()->route('profile')->with('success', 'Profile berhasil diupdate');
    }
}
