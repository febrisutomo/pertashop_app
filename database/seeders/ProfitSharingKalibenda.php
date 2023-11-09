<?php

namespace Database\Seeders;

use App\Models\Shop;
use App\Models\InvestorProfit;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProfitSharingKalibenda extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $profit_sharings = [
            [
                'id' => 1,
                'created_at' => '2021-10-30',
                'shop_id' => 2,
                'nilai_profit_sharing' => 6028393,
                'alokasi_modal' => 0,
            ],
            [
                'id' => 2,
                'created_at' => '2021-11-30',
                'shop_id' => 2,
                'nilai_profit_sharing' => 12658827,
                'alokasi_modal' => 0,
            ],
            [
                'id' => 3,
                'created_at' => '2021-12-30',
                'shop_id' => 2,
                'nilai_profit_sharing' => 14456427,
                'alokasi_modal' => 0,
            ],
            [
                'id' => 4,
                'created_at' => '2022-01-31',
                'shop_id' => 2,
                'nilai_profit_sharing' => 16378085,
                'alokasi_modal' => 0,
            ],
            [
                'id' => 5,
                'created_at' => '2022-02-28',
                'shop_id' => 2,
                'nilai_profit_sharing' => 11094659,
                'alokasi_modal' => 0,
            ],
            [
                'id' => 6,
                'created_at' => '2022-03-31',
                'shop_id' => 2,
                'nilai_profit_sharing' => 13196126,
                'alokasi_modal' => 0,
            ],
            [
                'id' => 7,
                'created_at' => '2022-04-30',
                'shop_id' => 2,
                'nilai_profit_sharing' => 7597664,
                'alokasi_modal' => 0,
            ],
            [
                'id' => 8,
                'created_at' => '2022-05-31',
                'shop_id' => 2,
                'nilai_profit_sharing' => 3571571,
                'alokasi_modal' => 0,
            ],
            [
                'id' => 9,
                'created_at' => '2022-06-30',
                'shop_id' => 2,
                'nilai_profit_sharing' => -470180,
                'alokasi_modal' => 0,
            ],
            [
                'id' => 10,
                'created_at' => '2022-07-31',
                'shop_id' => 2,
                'nilai_profit_sharing' => -789088,
                'alokasi_modal' => 0,
            ],
            [
                'id' => 11,
                'created_at' => '2022-08-31',
                'shop_id' => 2,
                'nilai_profit_sharing' => -703753,
                'alokasi_modal' => 0,
            ],
            [
                'id' => 12,
                'created_at' => '2022-09-30',
                'shop_id' => 2,
                'nilai_profit_sharing' => -925535,
                'alokasi_modal' => 0,
            ],
            [
                'id' => 13,
                'created_at' => '2022-10-31',
                'shop_id' => 2,
                'nilai_profit_sharing' => -136545,
                'alokasi_modal' => 0,
            ],
            [
                'id' => 14,
                'created_at' => '2022-11-30',
                'shop_id' => 2,
                'nilai_profit_sharing' => 0,
                'alokasi_modal' => 0,
            ],
            [
                'id' => 15,
                'created_at' => '2022-12-31',
                'shop_id' => 2,
                'nilai_profit_sharing' => -288452,
                'alokasi_modal' => 0,
            ],
            [
                'id' => 16,
                'created_at' => '2023-01-31',
                'shop_id' => 2,
                'nilai_profit_sharing' => -274891,
                'alokasi_modal' => 0,
            ],
            [
                'id' => 17,
                'created_at' => '2023-02-28',
                'shop_id' => 2,
                'nilai_profit_sharing' => 284035,
                'alokasi_modal' => 284035,
            ],
            [
                'id' => 18,
                'created_at' => '2023-03-31',
                'shop_id' => 2,
                'nilai_profit_sharing' => 263544,
                'alokasi_modal' => 263544,
            ],
            [
                'id' => 19,
                'created_at' => '2023-04-30',
                'shop_id' => 2,
                'nilai_profit_sharing' => 263797,
                'alokasi_modal' => 263797,
            ],
            [
                'id' => 20,
                'created_at' => '2023-05-31',
                'shop_id' => 2,
                'nilai_profit_sharing' => 494053,
                'alokasi_modal' => 494053,
            ],
            [
                'id' => 21,
                'created_at' => '2023-06-30',
                'shop_id' => 2,
                'nilai_profit_sharing' => 298207,
                'alokasi_modal' => 298207,
            ],
            [
                'id' => 22,
                'created_at' => '2023-07-31',
                'shop_id' => 2,
                'nilai_profit_sharing' => -955910,
                'alokasi_modal' => 0,
            ],
            [
                'id' => 23,
                'created_at' => '2023-08-31',
                'shop_id' => 2,
                'nilai_profit_sharing' => 5475819,
                'alokasi_modal' => 2839684,
            ],
            [
                'id' => 24,
                'created_at' => '2023-09-30',
                'shop_id' => 2,
                'nilai_profit_sharing' => 5778256,
                'alokasi_modal' => 577826,
            ],
        ];
        
        $kalibenda = Shop::find(2);

        $investor_profits = [];

        foreach ($profit_sharings as $profit_sharing) {
            foreach ($kalibenda->investors as $investor) {
                $investor_profit = [
                    'profit_sharing_id' => $profit_sharing['id'],
                    'investor_shop_id' => $investor->pivot->id,
                    'nilai_profit' => ($profit_sharing['nilai_profit_sharing'] - $profit_sharing['alokasi_modal']) * ($investor->pivot->investasi / 460000000),
                ];

                $investor_profits[] = $investor_profit;
            }
        }

        InvestorProfit::insert($investor_profits);
        
    }
}
