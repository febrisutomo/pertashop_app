<?php

namespace Database\Factories;

use App\Models\Sale;
use App\Models\Shop;
use App\Models\User;
use App\Models\Price;
use App\Models\Incoming;
use App\Models\Operator;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sale>
 */
class SaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [];
    }

    public function forShopId($shop_id): Factory
    {

        $shop = Shop::find($shop_id);
        $sales = Sale::where('shop_id', $shop_id);
        $operator = Operator::where('shop_id', $shop_id);

        $stok_awal = $shop->stok_awal;

        $penjualan_akhir = $sales->orderBy('id', 'desc')->first();


        $tanggal = $penjualan_akhir ? ($penjualan_akhir->created_at->format('H') == 15 ?  Carbon::createFromFormat('Y-m-d H:i:s', $penjualan_akhir->created_at)->setHour((21)) : Carbon::createFromFormat('Y-m-d H:i:s', $penjualan_akhir->created_at)->addDay()->setHour(15))  : '2023-08-01 15:00:00';
        $totalisator_awal = $penjualan_akhir ? $penjualan_akhir->totalisator_akhir : $shop->totalisator_awal;
        $operator_id = $penjualan_akhir?->created_at->format('H') == 15 ? $operator->orderBy('user_id', 'asc')->first()->id : $operator->orderBy('user_id', 'desc')->first()->id;

        $datang =  Incoming::where('shop_id', $shop_id)
            ->where('created_at', '<', $tanggal)->where('created_at', '>', $penjualan_akhir ? $penjualan_akhir->created_at : '2021-01-01')->get()->sum('jumlah');

        $min_penjualan = 300;
        $max_penjualan = 400;
        $penjualan = mt_rand() / mt_getrandmax() * ($max_penjualan - $min_penjualan) + $min_penjualan;
        $totalisator_akhir = $totalisator_awal + $penjualan;

        $total_penjualan = Sale::where('shop_id', $shop_id)->get()->sum('jumlah') +  $penjualan;
        $sisa_stok = $stok_awal + Incoming::where('shop_id', $shop_id)->get()->sum('jumlah') - $total_penjualan;

        $losses = 2 + (mt_rand() / mt_getrandmax()) * 2;

        $sisa_stok_akhir = ($penjualan_akhir ? $penjualan_akhir->stik_akhir : 142.85) * 21 + $datang - $penjualan - $losses;

        $stik_akhir = round($sisa_stok_akhir / 21, 2);

        $losses_gain = $stik_akhir * 21 - $sisa_stok;

        return $this->state(function (array $attributes) use ($shop_id, $tanggal, $operator_id, $totalisator_akhir, $totalisator_awal, $stik_akhir, $losses_gain) {
            return [
                'shop_id' => $shop_id,
                'created_at' => $tanggal,
                'operator_id' => $operator_id,
                'totalisator_awal' => $totalisator_awal,
                'totalisator_akhir' => $totalisator_akhir,
                'price_id' => Price::latest()->first()->id,
                'stik_akhir' => $stik_akhir,
                // 'losses_gain' => $losses_gain
            ];
        });
    }
}
