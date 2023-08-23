<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Http\Controllers\ReportController;
use App\Models\Sale;
use App\Models\Shop;
use App\Models\User;
use App\Models\Price;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Corporation;
use App\Models\Incoming;
use App\Models\Pengeluaran;
use App\Models\TestPump;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        $SAL = Corporation::factory()->create([
            'nama' => 'PT Serayu Agung Mandiri',
        ]);

        $SPA = Corporation::factory()->create([
            'nama' => 'PT Sinergy Petrajaya Abadi',
        ]);

        $KKB = Corporation::factory()->create([
            'nama' => 'KPRI Kokanaba Baturraden',
        ]);

        $kalitapen = Shop::factory()->for($SAL)->hasOperators(2)->create([
            'nama' => 'Kalitapen',
            'kode' => '4P.53119',
            'alamat' => 'Kel. Kalitapen Kec. Purwojati Kab. Banyumas',
        ]);
        $kalibenda = Shop::factory()->for($SAL)->hasOperators(2)->create([
            'nama' => 'Kalibenda',
            'kode' => '4P.53134',
            'alamat' => 'Kel. Kalibenda Kec. Ajibarang Kab. Banyumas',
        ]);
        $pageralang = Shop::factory()->for($SAL)->hasOperators(2)->create([
            'nama' => 'Pageralang',
            'kode' => '4P.53164',
            'alamat' => 'Kel. Pageralang Kec. Kemranjen Kab. Banyumas',
        ]);

        $gumelar = Shop::factory()->for($SPA)->hasOperators(2)->create([
            'nama' => 'Gumelar',
            'kode' => '4P.53158',
            'alamat' => 'Kel. Gumelar Kec. Gumelar Kab. Banyumas',
        ]);

        $kemutug = Shop::factory()->for($KKB)->hasOperators(2)->create([
            'nama' => 'Kemutug Lor',
            'kode' => '4P.53143',
            'alamat' => 'Kel. Kemutug Lor Kec. Baturraden Kab. Banyumas',
        ]);

        User::factory()->create([
            'name' => 'Dwi Yuliarto',
            'email' => 'admin@spa.com',
            'role' => 'admin'
        ]);

        User::factory()->create([
            'name' => 'Febri Sutomo',
            'email' => 'super-admin@spa.com',
            'role' => 'super-admin'
        ]);

        User::factory()->create([
            'email' => 'stockholder1@sal.com',
            'role' => 'stockholder'
        ]);


        Price::insert([
            [
                'harga_beli' => 13687.50,
                'harga_jual' => 14500,
                'created_at' => "2022-04-01"
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
            ]
        ]);


        Supplier::factory(3)->create();


        for ($i = 1; $i <= 6; $i++) {
            Sale::factory()->forShopId($kalitapen->id)->create();
            // Sale::factory()->forShopId($kalibenda->id)->create();
            // Sale::factory()->forShopId($pageralang->id)->create();
            // Sale::factory()->forShopId($gumelar->id)->create();
            // Sale::factory()->forShopId($kemutug->id)->create();
        }

        Price::create([
            'harga_beli' => 10250,
            'harga_jual' => 11500,
            'created_at' => "2023-08-03"
        ]);

        $purchase = Purchase::create([
            'shop_id' => $kalitapen->id,
            'supplier_id' => 1,
            'jumlah' => 22000,
            'price_id' => Price::latest()->first()->id,
            'created_at' => "2023-08-03"
        ]);


        Incoming::create([
            'shop_id' => 1,
            'purchase_id' => $purchase->id,
            'operator_id' => 2,
            'jumlah' => 2000,
            'stik_awal' => Sale::where('shop_id', 1)->latest()->first()->stik_akhir,
            'stik_akhir' => Sale::where('shop_id', 1)->latest()->first()->stik_akhir + 2000 / 21,
            'created_at' => "2023-08-04 14:00"
        ]);



        Sale::factory()->forShopId($kalitapen->id)->create();

        for ($i = 1; $i <= 5; $i++) {
            Sale::factory()->forShopId($kalitapen->id)->create();
        }

        TestPump::create([
            'shop_id' => $kalitapen->id,
            'operator_id' => 1,
            'created_at' => "2023-08-06 22:30",
            'totalisator_awal' => ReportController::calcLabaKotor($kalitapen->id)->last()['totalisator_akhir'],
            'totalisator_akhir' => ReportController::calcLabaKotor($kalitapen->id)->last()['totalisator_akhir'] + 200,
        ]);

        Pengeluaran::insert([
            [
                'shop_id' => $kalitapen->id,
                'created_at' => '2023-08-01',
                'deskripsi' => 'Gaji 2 Operator',
                'jumlah' => 2827612
            ],
            [
                'shop_id' => $kalitapen->id,
                'created_at' => '2023-08-01',
                'deskripsi' => 'Gaji Admin',
                'jumlah' => 500000
            ],
            [
                'shop_id' => $kalitapen->id,
                'created_at' => '2023-08-01',
                'deskripsi' => 'Biaya Curah',
                'jumlah' => 110000
            ],
            [
                'shop_id' => $kalitapen->id,
                'created_at' => '2023-08-01',
                'deskripsi' => 'Fotocopy & ATK',
                'jumlah' => 188000
            ],
            [
                'shop_id' => $kalitapen->id,
                'created_at' => '2023-08-01',
                'deskripsi' => 'Pulsa Listrik',
                'jumlah' => 53000
            ],
            [
                'shop_id' => $kalitapen->id,
                'created_at' => '2023-08-01',
                'deskripsi' => 'Iuran Warga',
                'jumlah' => 10000
            ],
        ]);
    }
}
