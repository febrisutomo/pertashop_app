<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Shop;
use App\Models\User;
use App\Models\Price;
use App\Models\Purchase;
use App\Models\Spending;
use App\Models\Supplier;
use App\Models\TestPump;
use App\Models\RekapModal;
use App\Models\Corporation;
use App\Models\InvestorProfit;
use App\Models\ProfitSharing;
use Illuminate\Database\Seeder;
use App\Models\SpendingCategory;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        //create corporation
        $SAL = Corporation::factory()->create([
            'nama' => 'PT Serayu Agung Mandiri',
            'alamat' => 'JL Kulon 674 RT 002 RW 003 Kel.Sudagaran Kec. Banyumas Kec. Banyumas Kab. Banyumas Prov. Jawa Tengah 53192'
        ]);

        $SPA = Corporation::factory()->create([
            'nama' => 'PT Sinergy Petrajaya Abadi',
            'alamat' => 'Desa Kalibenda RT2/1 kec. Ajibarang kab banyumas'
        ]);

        $KKB = Corporation::factory()->create([
            'nama' => 'KPRI Kokarnaba Baturraden',
            'alamat' => 'JL Raya Kemutug Lor Baturraden'
        ]);

        //create shop
        $kalitapen = Shop::factory()->for($SAL)->create([
            'nama' => 'Kalitapen',
            'kode' => '4P.53119',
            'short_name' => 'KLT',
            'alamat' => 'Kel. Kalitapen Kec. Purwojati Kab. Banyumas',
            'totalisator_awal' => 311404.970,
            'stik_awal' => 113,
            'modal_awal' => 60000000,
        ]);

        $kalibenda = Shop::factory()->for($SAL)->create([
            'nama' => 'Kalibenda',
            'short_name' => 'KLB',
            'kode' => '4P.53134',
            'alamat' => 'Kel. Kalibenda Kec. Ajibarang Kab. Banyumas',
            'totalisator_awal' => 303328.590,
            'stik_awal' => 120,
        ]);

        $pageralang = Shop::factory()->for($SAL)->create([
            'nama' => 'Pageralang',
            'short_name' => 'PGL',
            'kode' => '4P.53164',
            'alamat' => 'Kel. Pageralang Kec. Kemranjen Kab. Banyumas',
            'totalisator_awal' => 58543.424,
            'stik_awal' => 64,
            'skala' => 21.46
        ]);

        $gumelar = Shop::factory()->for($SPA)->create([
            'corporation_id' => $SPA->id,
            'nama' => 'Gumelar',
            'short_name' => 'GML',
            'kode' => '4P.53158',
            'alamat' => 'Kel. Gumelar Kec. Gumelar Kab. Banyumas',
            'totalisator_awal' => 113644.88,
            'stik_awal' => 98.20,
            'modal_awal' => 120000000
        ]);


        $kemutug = Shop::factory()->for($KKB)->create([
            'nama' => 'Kemutug Lor',
            'short_name' => 'KML',
            'kode' => '4P.53143',
            'alamat' => 'Kel. Kemutug Lor Kec. Baturraden Kab. Banyumas',
            'totalisator_awal' => 166491.170,
            'stik_awal' => 39.10,
            'skala' => 21.49
        ]);

        //create user with role super-admin
        User::factory()->create([
            'name' => 'Febri Sutomo',
            'email' => 'super-admin@pertashop.com',
            'role' => 'super-admin'
        ]);

        //create user with role admin
        User::factory()->for($kalitapen)->create([
            'name' => 'Dwi Yuliarto',
            'email' => 'admin@kalitapen.com',
            'role' => 'admin'
        ]);

        //create user with role operator
        User::factory()->for($kalitapen)->create([
            'name' => 'Muhammad Aulia Perdana',
            'email' => 'ardan@kalitapen.com',
            'role' => 'operator',
            'no_hp' => '085842539509',
            'alamat' => 'Kalitapen',
            'nama_bank' => 'BRI',
            'no_rekening' => '376001035647535',
            'pemilik_rekening' => 'Muhammad Aulia Perdana',
        ]);

        User::factory()->for($kalibenda)->create([
            'name' => 'Febriansah Saputra',
            'email' => 'febri@kalibenda.com',
            'role' => 'operator',
            'no_hp' => '0882007093173',
            'alamat' => 'Kalibenda',
            'nama_bank' => 'BRI',
            'no_rekening' => '660101016957538',
            'pemilik_rekening' => 'FEBRIANSAH SAPUTRA',
        ]);

        User::factory()->for($pageralang)->create([
            'name' => 'Rian Rizqy Milliarto',
            'email' => 'rian@pageralang.com',
            'role' => 'operator',
            'no_hp' => '085848184884',
            'alamat' => 'Pageralang',
            'nama_bank' => 'BRI',
            'no_rekening' => '682701018889533',
            'pemilik_rekening' => 'Rian Rizqy Milliarto',
        ]);

        User::factory()->for($gumelar)->create([
            'name' => 'Wiki Triono',
            'email' => 'wiki@gumelar.com',
            'role' => 'operator',
            'no_hp' => '08990840781',
            'alamat' => 'Gumelar',
            'nama_bank' => 'BRI',
            'no_rekening' => '376001006128530',
            'pemilik_rekening' => 'RUSWAN',
        ]);

        User::factory()->for($gumelar)->create([
            'name' => 'Dika Dwi Pratama',
            'email' => 'dika@gumelar.com',
            'role' => 'operator',
            'no_hp' => '0895322458546',
            'alamat' => 'Gumelar',
            'nama_bank' => 'BRI',
            'no_rekening' => '376001035647535',
            'pemilik_rekening' => 'DIKA DWI PRATAMA',
        ]);

        User::factory()->for($kemutug)->create([
            'name' => 'Suwitno',
            'email' => 'witno@kemutuglor.com',
            'role' => 'operator',
            'no_hp' => '85604234591',
            'alamat' => 'Kemutug Lor',
            'nama_bank' => 'BRI',
            'no_rekening' => '660501012523537',
            'pemilik_rekening' => 'Suwitno',
        ]);

        #create user with role investor
        $ptsam  = User::factory()->create([
            'name' => 'PT. Serayu Agung Mandiri',
            'email' => 'ptsam@pertashop.com',
            'role' => 'investor',
            'nama_bank' => 'Mandiri',
            'no_rekening' => '13900 2109 0000',
            'pemilik_rekening' => 'ADLAI BUDIARTO TJIPTO',
        ]);

        $victor = User::factory()->create([
            'name' => 'Victor Edward Asrikin',
            'email' => 'victor@pertashop.com',
            'role' => 'investor',
            'nama_bank' => 'Mandiri',
            'no_rekening' => '13900 1724 2391',
            'pemilik_rekening' => 'MARLINA NATALIA SETIAWAN',
        ]);

        $koko = User::factory()->create([
            'name' => 'Koko Aribowo',
            'email' => 'koko@pertashop.com',
            'role' => 'investor',
        ]);

        $kosim = User::factory()->create([
            'name' => 'Sugiyanto Kosim',
            'email' => 'kosim@pertashop.com',
            'role' => 'investor',
        ]);

        $kaswari = User::factory()->create([
            'name' => 'Kaswari',
            'email' => 'kaswari@pertashop.com',
            'role' => 'investor',
        ]);

        $adlai = User::factory()->create([
            'name' => 'R. Adlai BT Kalapaaking',
            'email' => 'adlai@pertashop.com',
            'role' => 'investor',
        ]);

        $eko = User::factory()->create([
            'name' => 'Eko Cahyonoo',
            'email' => 'eko@pertashop.com',
            'role' => 'investor',
        ]);


        #attach investor to shop
        #attach to shop kalitapen
        $ptsam->investments()->attach($kalitapen, [
            'persentase' => 70,
            'nama_bank' => 'Mandiri',
            'no_rekening' => '13900 2109 0000',
            'pemilik_rekening' => 'ADLAI BUDIARTO TJIPTO',
        ]);

        $victor->investments()->attach($kalitapen, [
            'persentase' => 15,
            'nama_bank' => 'Mandiri',
            'no_rekening' => '13900 1724 2391',
            'pemilik_rekening' => 'MARLINA NATALIA SETIAWAN',
        ]);

        $koko->investments()->attach($kalitapen, [
            'persentase' => 5,
            'nama_bank' => 'Mandiri',
            'no_rekening' => '90000 0679 3138',
            'pemilik_rekening' => 'KOKO ARIBOWO',
        ]);

        $kosim->investments()->attach($kalitapen, [
            'persentase' => 5,
            'nama_bank' => 'Mandiri',
            'no_rekening' => '13900 9204 6840',
            'pemilik_rekening' => 'SUGIYANTO KOSIM SINDU',
        ]);

        $kaswari->investments()->attach($kalitapen, [
            'persentase' => 5,
            'nama_bank' => 'BNI',
            'no_rekening' => '0436 8454 88',
            'pemilik_rekening' => 'KASWARI',
        ]);

        #attach to shop gumelar
        $koko->investments()->attach($gumelar, [
            'persentase' => 75,
            'nama_bank' => 'BCA',
            'no_rekening' => '7510 6699 96',
            'pemilik_rekening' => 'PT. TRIMITRA CIPTA KREASI',
        ]);

        $adlai->investments()->attach($gumelar, [
            'persentase' => 15,
            'nama_bank' => 'Mandiri',
            'no_rekening' => '13900 2109 0000',
            'pemilik_rekening' => 'R. ADLAI BT KALAPAAKING',
        ]);

        $eko->investments()->attach($gumelar, [
            'persentase' => 10,
            'nama_bank' => 'BCA',
            'no_rekening' => '4240 2645 82',
            'pemilik_rekening' => 'EKO CAHYONO',
        ]);

        Price::insert([
            [
                'harga_beli' => 8173.50,
                'harga_jual' => 9000,
                'created_at' => "2021-01-01"
            ],
            [
                'harga_beli' => 11682.30,
                'harga_jual' => 12500,
                'created_at' => "2021-04-01"
            ],
            [
                'harga_beli' => 13687.50,
                'harga_jual' => 14500,
                'created_at' => "2022-03-09"
            ],
            [
                'harga_beli' => 13085.95,
                'harga_jual' => 13900,
                'created_at' => "2022-10-01"
            ],
            [
                'harga_beli' => 13079.96,
                'harga_jual' => 13900,
                'created_at' => "2022-09-30"
            ],
            [
                'harga_beli' => 11977.59,
                'harga_jual' => 12800,
                'created_at' => "2023-01-03"
            ],
            [
                'harga_beli' => 12478.66,
                'harga_jual' => 13300,
                'created_at' => "2023-03-01"
            ],
            [
                'harga_beli' => 11676.94,
                'harga_jual' => 12500,
                'created_at' => "2023-06-01"
            ],
            [
                'harga_beli' => 12478.66,
                'harga_jual' => 13300,
                'created_at' => "2023-09-01"
            ]
        ]);


        Supplier::factory(3)->create();


        // $purchase = Purchase::create([
        //     'no_so' => 123456,
        //     'shop_id' => $kalitapen->id,
        //     'supplier_id' => 1,
        //     'volume' => 2000,
        //     'created_at' => "2023-08-28",
        //     'total_bayar' => 2000 * Price::latest()->first()->harga_beli,
        // ]);


        // Incoming::create([
        //     'shop_id' => 1,
        //     'purchase_id' => $purchase->id,
        //     'operator_id' => 1,
        //     'sopir' => 'Andi',
        //     'no_polisi' => 'R 3242 JK',
        //     'volume' => 2000,
        //     'stik_awal' => 60,
        //     'stik_akhir' => 155.3,
        //     'created_at' => now()
        // ]);

        SpendingCategory::insert([
            ['nama' => 'Ongkos Bongkar'],
            ['nama' => 'Biaya Transfer'],
            ['nama' => 'Fotocopy & ATK'],
            ['nama' => 'Listrik'],
            ['nama' => 'Air Bersih'],
            ['nama' => 'Cashback'],
            ['nama' => 'Internet']
        ]);

        SpendingCategory::create(['id' => 99, 'nama' => 'Lain-lain']);

        // Spending::insert([
        //     [
        //         'shop_id' => $kalitapen->id,
        //         'operator_id' => 1,
        //         'created_at' => now(),
        //         'spending_category_id' => 3,
        //         'jumlah' => 10000
        //     ],
        //     [
        //         'shop_id' => $kalitapen->id,
        //         'operator_id' => 1,
        //         'created_at' => now(),
        //         'spending_category_id' => 5,
        //         'jumlah' => 1800
        //     ],
        //     [
        //         'shop_id' => $kalitapen->id,
        //         'operator_id' => 1,
        //         'created_at' => now(),
        //         'spending_category_id' => 6,
        //         'jumlah' => 105000
        //     ],
        // ]);

        // Spending::create([
        //     'shop_id' => $kalitapen->id,
        //     'operator_id' => 1,
        //     'created_at' => now(),
        //     'spending_category_id' => 99,
        //     'keterangan' => 'Seragam',
        //     'jumlah' => 110000
        // ]);

        // TestPump::create([
        //     'created_at' => now(),
        //     'totalisator_awal' => 120000,
        //     'totalisator_akhir' => 120010,
        //     'operator_id' => 1,
        //     'shop_id' => $kalitapen->id
        // ]);

        $rekapModal = [
            [
                'shop_id' => $kalitapen->id,
                'created_at' => '2021-06-30',
                'rugi' => 0,
                'pajak_bank' => 0,
                'alokasi_keuntungan' => 0,
                'bunga_bank' => 0,
            ],
            [
                'shop_id' => $kalitapen->id,
                'created_at' => '2021-07-31',
                'rugi' => 0,
                'pajak_bank' => 9675,
                'alokasi_keuntungan' => 0,
                'bunga_bank' => 15875,
            ],
            [
                'shop_id' => $kalitapen->id,
                'created_at' => '2021-08-31',
                'rugi' => 0,
                'pajak_bank' => 20232,
                'alokasi_keuntungan' => 0,
                'bunga_bank' => 8659,
            ],
            [
                'shop_id' => $kalitapen->id,
                'created_at' => '2021-09-30',
                'rugi' => 0,
                'pajak_bank' => 20549,
                'alokasi_keuntungan' => 0,
                'bunga_bank' => 10244,
            ],
            [
                'shop_id' => $kalitapen->id,
                'created_at' => '2021-10-31',
                'rugi' => 0,
                'pajak_bank' => 9230,
                'alokasi_keuntungan' => 0,
                'bunga_bank' => 11149,
            ],
            [
                'shop_id' => $kalitapen->id,
                'created_at' => '2021-11-30',
                'rugi' => 0,
                'pajak_bank' => 5883,
                'alokasi_keuntungan' => 0,
                'bunga_bank' => 9414,
            ],
            [
                'shop_id' => $kalitapen->id,
                'created_at' => '2021-12-31',
                'rugi' => 0,
                'pajak_bank' => 2230,
                'alokasi_keuntungan' => 0,
                'bunga_bank' => 11150,
            ],
            [
                'shop_id' => $kalitapen->id,
                'created_at' => '2022-02-28',
                'rugi' => 5476183, // Merubah nilai negatif menjadi positif
                'pajak_bank' => 11983, // Merubah nilai negatif menjadi positif
                'alokasi_keuntungan' => 0,
                'bunga_bank' => 7417,
            ],
            [
                'shop_id' => $kalitapen->id,
                'created_at' => '2022-03-31',
                'rugi' => 0,
                'pajak_bank' => 5437,
                'alokasi_keuntungan' => 0,
                'bunga_bank' => 7185,
            ],
            [
                'shop_id' => $kalitapen->id,
                'created_at' => '2022-04-30',
                'rugi' => 0,
                'pajak_bank' => 8841,
                'alokasi_keuntungan' => 0,
                'bunga_bank' => 9205,
            ],
            [
                'shop_id' => $kalitapen->id,
                'created_at' => '2022-05-31',
                'rugi' => 0,
                'pajak_bank' => 11564,
                'alokasi_keuntungan' => 0,
                'bunga_bank' => 10319,
            ],
            [
                'shop_id' => $kalitapen->id,
                'created_at' => '2022-06-30',
                'rugi' => 0,
                'pajak_bank' => 9675,
                'alokasi_keuntungan' => 0,
                'bunga_bank' => 8783,
            ],
            [
                'shop_id' => $kalitapen->id,
                'created_at' => '2022-07-31',
                'rugi' => 0,
                'pajak_bank' => 5410,
                'alokasi_keuntungan' => 0,
                'bunga_bank' => 7048,
            ],
            [
                'shop_id' => $kalitapen->id,
                'created_at' => '2022-08-31',
                'rugi' => 0,
                'pajak_bank' => 10277,
                'alokasi_keuntungan' => 0,
                'bunga_bank' => 6385,
            ],
            [
                'shop_id' => $kalitapen->id,
                'created_at' => '2022-09-30',
                'rugi' => 0,
                'pajak_bank' => 4443,
                'alokasi_keuntungan' => 0,
                'bunga_bank' => 7215,
            ],
            [
                'shop_id' => $kalitapen->id,
                'created_at' => '2022-10-31',
                'rugi' => 1654207, // Merubah nilai negatif menjadi positif
                'pajak_bank' => 2710, // Merubah nilai negatif menjadi positif
                'alokasi_keuntungan' => 0,
                'bunga_bank' => 13503,
            ],
            [
                'shop_id' => $kalitapen->id,
                'created_at' => '2022-11-30',
                'rugi' => 0,
                'pajak_bank' => 2124,
                'alokasi_keuntungan' => 0,
                'bunga_bank' => 10620,
            ],
            [
                'shop_id' => $kalitapen->id,
                'created_at' => '2022-12-31',
                'rugi' => 0,
                'pajak_bank' => 1709,
                'alokasi_keuntungan' => 0,
                'bunga_bank' => 8543,
            ],
            [
                'shop_id' => $kalitapen->id,
                'created_at' => '2023-01-31',
                'rugi' => 949043, // Merubah nilai negatif menjadi positif
                'pajak_bank' => 6191, // Merubah nilai negatif menjadi positif
                'alokasi_keuntungan' => 0,
                'bunga_bank' => 10954,
            ],
            [
                'shop_id' => $kalitapen->id,
                'created_at' => '2023-02-28',
                'rugi' => 0,
                'pajak_bank' => 2089,
                'alokasi_keuntungan' => 169469,
                'bunga_bank' => 10444,
            ],
            [
                'shop_id' => $kalitapen->id,
                'created_at' => '2023-03-31',
                'rugi' => 0,
                'pajak_bank' => 5873,
                'alokasi_keuntungan' => 240952,
                'bunga_bank' => 9366,
            ],
            [
                'shop_id' => $kalitapen->id,
                'created_at' => '2023-04-30',
                'rugi' => 125970, // Merubah nilai negatif menjadi positif
                'pajak_bank' => 2103, // Merubah nilai negatif menjadi positif
                'alokasi_keuntungan' => 0,
                'bunga_bank' => 10515,
            ],
            [
                'shop_id' => $kalitapen->id,
                'created_at' => '2023-05-31',
                'rugi' => 0,
                'pajak_bank' => 1863,
                'alokasi_keuntungan' => 122400,
                'bunga_bank' => 9314,
            ],
            [
                'shop_id' => $kalitapen->id,
                'created_at' => '2023-06-30',
                'rugi' => 0,
                'pajak_bank' => 5895,
                'alokasi_keuntungan' => 54135,
                'bunga_bank' => 9473,
            ],
            [
                'shop_id' => $kalitapen->id,
                'created_at' => '2023-07-31',
                'rugi' => 0,
                'pajak_bank' => 1405,
                'alokasi_keuntungan' => 571077,
                'bunga_bank' => 7024,
            ],
        ];

        RekapModal::insert($rekapModal);
        RekapModal::where('shop_id', $kalitapen->id)->update(['kas_kecil' => 600000]);

        $profit_sharings = [
            [
                'id' => 1,
                'created_at' => '2021-07-31',
                'shop_id' => 1,
                'nilai_profit_sharing' => 2128174,
                'alokasi_modal' => 0,
            ],
            [
                'id' => 2,
                'created_at' => '2021-08-30',
                'shop_id' => 1,
                'nilai_profit_sharing' => 6667094,
                'alokasi_modal' => 0,
            ],
        ];


        $investors = [
            [
                'investor_shop_id' => $ptsam->investments()->where('shop_id', $kalitapen->id)->first()->pivot->id,
                'persentase' => 100,
            ]
        ];

        ProfitSharing::insert($profit_sharings);

        $investor_profits = [];

        foreach ($profit_sharings as $profit_sharing) {
            foreach ($investors as $investor) {
                $investor_profit = [
                    'profit_sharing_id' => $profit_sharing['id'],
                    'investor_shop_id' => $investor['investor_shop_id'],
                    'nilai_profit' => $profit_sharing['nilai_profit_sharing'] * ($investor['persentase'] / 100),
                ];

                $investor_profits[] = $investor_profit;
            }
        }

        InvestorProfit::insert($investor_profits);

        $profit_sharings = [
            [
                'id' => 3,
                'created_at' => '2021-09-30',
                'shop_id' => 1,
                'nilai_profit_sharing' => 10641659,
                'alokasi_modal' => 0,
            ],
            [
                'id' => 4,
                'created_at' => '2021-10-31',
                'shop_id' => 1,
                'nilai_profit_sharing' => 9990141,
                'alokasi_modal' => 0,
            ],
            [
                'id' => 5,
                'created_at' => '2021-11-30',
                'shop_id' => 1,
                'nilai_profit_sharing' => 11202889,
                'alokasi_modal' => 0,
            ],
            [
                'id' => 6,
                'created_at' => '2021-12-31',
                'shop_id' => 1,
                'nilai_profit_sharing' => 13906102,
                'alokasi_modal' => 0,
            ],
            [
                'id' => 7,
                'created_at' => '2022-01-31',
                'shop_id' => 1,
                'nilai_profit_sharing' => 5183149,
                'alokasi_modal' => 0,
            ],
            [
                'id' => 8,
                'created_at' => '2022-02-28',
                'shop_id' => 1,
                'nilai_profit_sharing' => -5476183,
                'alokasi_modal' => 0,
            ],
            [
                'id' => 9,
                'created_at' => '2022-03-31',
                'shop_id' => 1,
                'nilai_profit_sharing' => 5046982,
                'alokasi_modal' => 0,
            ],
            [
                'id' => 10,
                'created_at' => '2022-04-30',
                'shop_id' => 1,
                'nilai_profit_sharing' => 6648855,
                'alokasi_modal' => 0,
            ],
            [
                'id' => 11,
                'created_at' => '2022-05-31',
                'shop_id' => 1,
                'nilai_profit_sharing' => 7024491,
                'alokasi_modal' => 0,
            ],
            [
                'id' => 12,
                'created_at' => '2022-06-30',
                'shop_id' => 1,
                'nilai_profit_sharing' => 3584955,
                'alokasi_modal' => 0,
            ],
            [
                'id' => 13,
                'created_at' => '2022-07-31',
                'shop_id' => 1,
                'nilai_profit_sharing' => 2030129,
                'alokasi_modal' => 0,
            ],
            [
                'id' => 14,
                'created_at' => '2022-08-31',
                'shop_id' => 1,
                'nilai_profit_sharing' => 956151,
                'alokasi_modal' => 0,
            ],
            [
                'id' => 15,
                'created_at' => '2022-09-30',
                'shop_id' => 1,
                'nilai_profit_sharing' => 347415,
                'alokasi_modal' => 0,
            ],
            [
                'id' => 16,
                'created_at' => '2022-10-31',
                'shop_id' => 1,
                'nilai_profit_sharing' => -1654207,
                'alokasi_modal' => 0,
            ],
            [
                'id' => 17,
                'created_at' => '2022-11-30',
                'shop_id' => 1,
                'nilai_profit_sharing' => 628484,
                'alokasi_modal' => 0,
            ],
            [
                'id' => 18,
                'created_at' => '2022-12-31',
                'shop_id' => 1,
                'nilai_profit_sharing' => 1647416,
                'alokasi_modal' => 0,
            ],
            [
                'id' => 19,
                'created_at' => '2023-01-31',
                'shop_id' => 1,
                'nilai_profit_sharing' => -949043,
                'alokasi_modal' => 0,
            ],
            [
                'id' => 20,
                'created_at' => '2023-02-28',
                'shop_id' => 1,
                'nilai_profit_sharing' => 1694688,
                'alokasi_modal' => 169469,
            ],
            [
                'id' => 21,
                'created_at' => '2023-03-31',
                'shop_id' => 1,
                'nilai_profit_sharing' => 2409518,
                'alokasi_modal' => 240952,
            ],
            [
                'id' => 22,
                'created_at' => '2023-04-30',
                'shop_id' => 1,
                'nilai_profit_sharing' => -125970,
                'alokasi_modal' => 0,
            ],
            [
                'id' => 23,
                'created_at' => '2023-05-31',
                'shop_id' => 1,
                'nilai_profit_sharing' => 1224004,
                'alokasi_modal' => 122400,
            ],
            [
                'id' => 24,
                'created_at' => '2023-06-30',
                'shop_id' => 1,
                'nilai_profit_sharing' => 541354,
                'alokasi_modal' => 54135,
            ],
            [
                'id' => 25,
                'created_at' => '2023-07-31',
                'shop_id' => 1,
                'nilai_profit_sharing' => 571077,
                'alokasi_modal' => 571077,
            ],
        ];


        ProfitSharing::insert($profit_sharings);

        $kalitapen = Shop::find($kalitapen->id);

        $investor_profits = [];

        foreach ($profit_sharings as $profit_sharing) {
            foreach ($kalitapen->investors as $investor) {
                $investor_profit = [
                    'profit_sharing_id' => $profit_sharing['id'],
                    'investor_shop_id' => $investor->pivot->id,
                    'nilai_profit' => ($profit_sharing['nilai_profit_sharing'] - $profit_sharing['alokasi_modal']) * ($investor->pivot->persentase / 100),
                ];

                $investor_profits[] = $investor_profit;
            }
        }

        // print($investor_profits);

        InvestorProfit::insert($investor_profits);
    }
}
