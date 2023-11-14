<?php

namespace Database\Seeders;

use App\Models\Shop;
use App\Models\ProfitSharing;
use App\Models\InvestorProfit;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProfitSharingKemutug extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shop = Shop::find(5);

        $profit_sharings = [
            [
                'created_at' => '2021-12-31',
                'nilai_profit_sharing' => 1390276,
                'alokasi_modal' => 0,
            ],
            [
                'created_at' => '2022-01-31',
                'nilai_profit_sharing' => 8427359,
                'alokasi_modal' => 0,
            ],
            [
                'created_at' => '2022-02-28',
                'nilai_profit_sharing' => 5843448,
                'alokasi_modal' => 0,
            ],
            [
                'created_at' => '2022-03-31',
                'nilai_profit_sharing' => 6731907,
                'alokasi_modal' => 0,
            ],
            [
                'created_at' => '2022-04-30',
                'nilai_profit_sharing' => 1020159,
                'alokasi_modal' => 0,
            ],
            [
                'created_at' => '2022-05-31',
                'nilai_profit_sharing' => 4805752,
                'alokasi_modal' => 0,
            ],
            [
                'created_at' => '2022-06-30',
                'nilai_profit_sharing' => 2396638,
                'alokasi_modal' => 0,
            ],
            [
                'created_at' => '2022-07-31',
                'nilai_profit_sharing' => 1933909,
                'alokasi_modal' => 0,
            ],
            [
                'created_at' => '2022-08-31',
                'nilai_profit_sharing' => 848489,
                'alokasi_modal' => 0,
            ],
            [
                'created_at' => '2022-09-30',
                'nilai_profit_sharing' => 137073,
                'alokasi_modal' => 0,
            ],
            [
                'created_at' => '2022-10-31',
                'nilai_profit_sharing' => -1647272,
                'alokasi_modal' => 0,
            ],
            [
                'created_at' => '2022-11-30',
                'nilai_profit_sharing' => -471753,
                'alokasi_modal' => 0,
            ],
            [
                'created_at' => '2022-12-31',
                'nilai_profit_sharing' => -2624100,
                'alokasi_modal' => 0,
            ],
            [
                'created_at' => '2023-01-31',
                'nilai_profit_sharing' => -2282711,
                'alokasi_modal' => 0,
            ],
            [
                'created_at' => '2023-02-28',
                'nilai_profit_sharing' => -814916,
                'alokasi_modal' => 0,
            ],
            [
                'created_at' => '2023-03-31',
                'nilai_profit_sharing' => -2834526,
                'alokasi_modal' => 0,
            ],
            [
                'created_at' => '2023-04-30',
                'nilai_profit_sharing' => -2924432,
                'alokasi_modal' => 0,
            ],
            [
                'created_at' => '2023-05-31',
                'nilai_profit_sharing' => -1103017,
                'alokasi_modal' => 0,
            ],
            [
                'created_at' => '2023-06-30',
                'nilai_profit_sharing' => -2039648,
                'alokasi_modal' => 0,
            ],
            [
                'created_at' => '2023-07-31',
                'nilai_profit_sharing' => 911426,
                'alokasi_modal' => 0,
            ],
            [
                'created_at' => '2023-08-31',
                'nilai_profit_sharing' => 1269205,
                'alokasi_modal' => 0,
            ],
            [
                'created_at' => '2023-09-30',
                'nilai_profit_sharing' => 1010744,
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
