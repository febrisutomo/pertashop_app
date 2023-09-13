<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\DailyReport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ProfitSharingController extends Controller
{
    public function index(Request $request)
    {

        $shops = Shop::all();

        if (Auth::user()->role == 'investor') {
            $shops = Auth::user()->investments;
            $shop_id = $request->input('shop_id', 1);
        } elseif (Auth::user()->role == 'super-admin') {
            $shop_id = $request->input('shop_id', 1);
        } else {
            $shop_id = Auth::user()->shop_id;
        }

        $shop = Shop::find($shop_id);

        $sales = DailyReport::where('shop_id', $shop_id)->get()->groupBy(function ($item) {
            return $item->created_at->format('Y-m');
        });


        $sharings = $sales->map(function ($value, $bulan) use ($shop) {

            $laba_bersih = LabaBersihController::getLabaBersih($shop->id, $bulan);

            $investor_profit = [];

            foreach ($shop->investors as $key => $investor) {
                $investor_profit[strtolower(str_replace(' ', '_', $investor->name))] = $laba_bersih['laba_bersih_dibagi'] * $investor->pivot->persentase_keuntungan / 100;
            }

            $data = [
                'shop_id' => $shop->id,
                'bulan' => $bulan,
                'alokasi_modal' => 0,
                'profit_sharing' => 0,
                'sisa_keuntungan' => 0,
                'roi' => 0,
            ];

            return collect($data)->merge($investor_profit)->toArray();
        });
        

        return view('profit_sharing.index', compact('shops',  'sharings', 'shop'));
    }
}
