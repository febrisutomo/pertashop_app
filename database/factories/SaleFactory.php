<?php

namespace Database\Factories;

use App\Models\Operator;
use App\Models\Sale;
use App\Models\Shop;
use App\Models\User;
use App\Models\Price;
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
        $sale = Sale::where('shop_id', $shop_id);
        $operator = Operator::where('shop_id', $shop_id);

        $stok_awal = $shop->stok_awal;
        $skala = 21;
        $penjualan_akhir = $sale->orderBy('id', 'desc')->first();

        $tanggal = $penjualan_akhir ? ($penjualan_akhir->created_at->format('H') == 15 ?  Carbon::createFromFormat('Y-m-d H:i:s', $penjualan_akhir->created_at)->setHour((21)) : Carbon::createFromFormat('Y-m-d H:i:s', $penjualan_akhir->created_at)->addDay()->setHour(15))  : '2023-08-01 15:00:00';
        $totalisator_awal = $penjualan_akhir ? $penjualan_akhir->totalisator_akhir : $shop->totalisator_akhir;
        $operator_id = $penjualan_akhir?->created_at->format('H') == 15 ? $operator->orderBy('user_id', 'asc')->first()->id : $operator->orderBy('user_id', 'desc')->first()->id;

        $min_penjualan = 300;
        $max_penjualan = 400;
        $penjualan = mt_rand() / mt_getrandmax() * ($max_penjualan - $min_penjualan) + $min_penjualan;
        $totalisator_akhir = $totalisator_awal + $penjualan;

        $total_penjualan = Sale::where('shop_id', $shop_id)->get()->sum('jumlah') +  $penjualan;
        $sisa_stok = $stok_awal - $total_penjualan;

        $losses = 2 + (mt_rand() / mt_getrandmax()) * 2;

        $sisa_stok_akhir = ($penjualan_akhir ? $penjualan_akhir->stik_akhir : 142.85) * $skala - $penjualan - $losses;
        
        $stik_akhir = round($sisa_stok_akhir / $skala, 2);

        $losses_gain = ($stik_akhir * $skala - $sisa_stok) / $total_penjualan * 100;

        return $this->state(function (array $attributes) use ($shop_id, $tanggal, $operator_id, $totalisator_akhir, $totalisator_awal, $stik_akhir, $losses_gain) {
            return [
                'shop_id' => $shop_id,
                'created_at' => $tanggal,
                'operator_id' => $operator_id,
                'totalisator_awal' => $totalisator_awal,
                'totalisator_akhir' => $totalisator_akhir,
                'price_id' => Price::latest()->first()->id,
                'stik_akhir' => $stik_akhir,
                'losses_gain' => $losses_gain
            ];
        })->afterCreating(function (Sale $sale) use ($totalisator_akhir, $stik_akhir) {
            $sale->shop()->update([
                'totalisator_akhir' => $totalisator_akhir,
                'stik_akhir' => $stik_akhir
            ]);
        });
    }
}
