<?php

namespace Database\Seeders;

use App\Models\Shop;
use App\Models\ProfitSharing;
use App\Models\InvestorProfit;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProfitSharingPageralang extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shop = Shop::find(3);

        $profit_sharings = [
            [
                'created_at' => '2022-11-30',
                'nilai_profit_sharing' => -4965219,
                'alokasi_modal' => 0,
            ],
            [
                'created_at' => '2022-12-31',
                'nilai_profit_sharing' => -6347272,
                'alokasi_modal' => 0,
            ],
            [
                'created_at' => '2023-01-31',
                'nilai_profit_sharing' => -3478324,
                'alokasi_modal' => 0,
            ],
            [
                'created_at' => '2023-02-28',
                'nilai_profit_sharing' => -1426506,
                'alokasi_modal' => 0,
            ],
            [
                'created_at' => '2023-03-31',
                'nilai_profit_sharing' => -24910,
                'alokasi_modal' => 0,
            ],
            [
                'created_at' => '2023-04-30',
                'nilai_profit_sharing' => -2528022,
                'alokasi_modal' => 0,
            ],
            [
                'created_at' => '2023-05-31',
                'nilai_profit_sharing' => -865252,
                'alokasi_modal' => 0,
            ],
            [
                'created_at' => '2023-06-30',
                'nilai_profit_sharing' => -2821524,
                'alokasi_modal' => 0,
            ],
            [
                'created_at' => '2023-07-31',
                'nilai_profit_sharing' => 257932,
                'alokasi_modal' => 0,
            ],
            [
                'created_at' => '2023-08-31',
                'nilai_profit_sharing' => 176960,
                'alokasi_modal' => 0,
            ],
            [
                'created_at' => '2023-09-30',
                'nilai_profit_sharing' => 438354,
                'alokasi_modal' => 0,
            ],
        ];
        
        
        foreach ($profit_sharings as $profit_sharing) {
            $profit_sharing['shop_id'] = $shop->id;
            $profitSharingModel = ProfitSharing::create($profit_sharing);

            $investor_profits = [];

            foreach ($shop->investors as $investor) {
                $investor_profit = [
                    'profit_sharing_id' => $profitSharingModel->id,
                    'investor_shop_id' => $investor->pivot->id,
                    'nilai_profit' => ($profitSharingModel->nilai_profit_sharing - $profitSharingModel->alokasi_modal) * ($investor->pivot->investasi / 460000000),
                ];

                $investor_profits[] = $investor_profit;
            }

            InvestorProfit::insert($investor_profits);
        }
        
    }
}
