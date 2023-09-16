<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\InvestorProfit;
use App\Models\ProfitSharing;
use Illuminate\Support\Facades\Auth;

class ProfitSharingController extends Controller
{
    public function index(Request $request)
    {

        if (Auth::user()->role == 'admin') {
            $shop_id = Auth::user()->shop_id;
        } else {
            $shop_id = $request->input('shop_id', 1);
        }

        $shops = Shop::all();
        if (Auth::user()->role == 'investor') {
            $shops = Auth::user()->investments;
        }

        $year = $request->input('year', date('Y'));

        $shop = Shop::find($shop_id);

        $profitSharings = ProfitSharing::with('shop', 'investorProfits')->where('shop_id', $shop_id)->whereYear('created_at', $year)->oldest()->get();

        $investorProfits = InvestorProfit::where('profit_sharing_id', 1)->get();

        // dd($shop->investors->first()->pivot->profits);

        return view('profit_sharing.index', compact('shops',  'profitSharings', 'shop'));
    }
}
