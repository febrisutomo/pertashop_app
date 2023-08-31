<?php

namespace App\Models;

use App\Models\Shop;
use App\Models\Price;
use App\Models\Incoming;
use App\Models\Operator;
use App\Models\Spending;
use App\Models\TestPump;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DailyReport extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = [
        'totalisator_awal',
        'stik_awal',
        'stok_awal',
        'stok_akhir_aktual',
        'test_pump',
        'penerimaan',
        'volume_penjualan',
        'stok_akhir_teoritis',
        'losses_gain',
        'pengeluaran',
        'rupiah_penjualan',
        'pendapatan',
        'selisih_setoran',
        'belum_disetorkan'
    ];

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function incomings()
    {
        return $this->hasMany(Incoming::class);
    }

    public function testPumps()
    {
        return $this->hasMany(TestPump::class);
    }

    public function spendings()
    {
        return $this->hasMany(Spending::class);
    }

    public function price()
    {
        return $this->belongsTo(Price::class);
    }

    protected function latestByShop()
    {
        return $this->where('shop_id', $this->shop->id)->where('created_at', '<', $this->created_at)->latest()->first();
    }

    protected function latestByOperator()
    {
        return $this->where('operator_id', $this->operator->id)->where('created_at', '<', $this->created_at)->latest()->first();
    }

    public function getTotalisatorAwalAttribute()
    {
        return $this->latestByShop() ?  $this->latestByShop()->totalisator_akhir : $this->shop->totalisator_awal;
    }

    public function getStikAwalAttribute()
    {
        return  $this->latestByShop() ?  $this->latestByShop()->stik_akhir : $this->shop->stik_awal;
    }

    public function getStokAwalAttribute()
    {
        return $this->stik_awal * $this->shop->skala;
    }

    public function getStokAkhirAktualAttribute()
    {
        return $this->stik_akhir * $this->shop->skala;
    }

    public function getTestPumpAttribute()
    {
        return $this->testPumps->sum('volume');
    }

    public function getPenerimaanAttribute()
    {
        return $this->incomings->sum('volume');
    }

    public function getVolumePenjualanAttribute()
    {
        return $this->totalisator_akhir - $this->totalisator_awal - $this->test_pump;
    }

    public function getStokAkhirTeoritisAttribute()
    {
        return round($this->stok_awal + $this->penerimaan - $this->volume_penjualan, 2);
    }

    public function getLossesGainAttribute()
    {
        return round($this->stok_akhir_aktual - $this->stok_akhir_teoritis, 3);
    }

    public function getPengeluaranAttribute()
    {
        return $this->spendings->sum('jumlah');
    }

    public function getPendapatanAttribute()
    {
        return $this->rupiah_penjualan - $this->pengeluaran;
    }

    public function getRupiahPenjualanAttribute()
    {
        return $this->price->harga_jual * $this->volume_penjualan;
    }

    public function getSelisihSetoranAttribute()
    {
        return $this->disetorkan - $this->pendapatan;
    }

    public function getBelumDisetorkanAttribute()
    {
        return $this->latestByOperator() ? $this->latestByOperator()->belum_disetorkan + $this->selisih_setoran : $this->selisih_setoran;
    }
}
