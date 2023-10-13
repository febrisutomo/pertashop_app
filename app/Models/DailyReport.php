<?php

namespace App\Models;

use App\Models\Shop;
use App\Models\User;
use App\Models\Price;
use App\Models\Incoming;
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
        'percobaan',
        'penerimaan',
        'volume_penjualan',
        'stok_akhir_teoritis',
        'losses_gain',
        'pengeluaran',
        'rupiah_penjualan',
        'pendapatan',
        'selisih_setoran',
        'tabungan',
        'tanggal_panjang',
        'tanggal',
    ];

    public function operator()
    {
        return $this->belongsTo(User::class);
    }
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function incoming()
    {
        return $this->hasOne(Incoming::class);
    }

    public function testPump()
    {
        return $this->hasOne(TestPump::class);
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

    protected function yesterday()
    {
        return $this->where('shop_id', $this->shop->id)->whereDate('created_at', '<', $this->created_at->format('Y-m-d'))->latest()->first();
    }

    protected function latestByOperator()
    {
        return $this->where('operator_id', $this->operator->id)->where('created_at', '<', $this->created_at)->latest()->first();
    }

    protected function today()
    {
        return $this->where('shop_id', $this->shop->id)->whereDate('created_at', $this->created_at->format('Y-m-d'))->get();
    }

    public function getTotalisatorAwalAttribute()
    {
        return $this->latestByShop() ?  $this->latestByShop()->totalisator_akhir : $this->shop->totalisator_awal;
    }

    public function getStikAwalAttribute()
    {
        return  $this->yesterday() ?  $this->yesterday()->stik_akhir : $this->shop->stik_awal;
    }

    public function getStokAwalAttribute()
    {
        return $this->stik_awal * $this->shop->skala;
    }

    public function getStokAkhirAktualAttribute()
    {
        return $this->stik_akhir ? round($this->stik_akhir * $this->shop->skala, 2) : null;
    }

    public function getPercobaanAttribute()
    {
        return $this->testPump?->volume_test;
    }

    public function getPenerimaanAttribute()
    {
        return $this->incoming?->volume;
    }

    public function getVolumePenjualanAttribute()
    {
        return $this->totalisator_akhir - $this->totalisator_awal - $this->percobaan;
    }

    public function getStokAkhirTeoritisAttribute()
    {
        return $this->stik_akhir ? round($this->stok_awal + $this->today()->sum('penerimaan') - $this->today()->sum('volume_penjualan'), 2) : round($this->stok_awal + $this->penerimaan - $this->volume_penjualan, 2);
    }

    public function getLossesGainAttribute()
    {
        return $this->stik_akhir ? round($this->stok_akhir_aktual - $this->stok_akhir_teoritis, 2) : null;
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
        return round($this->price->harga_jual * ($this->totalisator_akhir - $this->totalisator_awal - $this->percobaan));
    }

    public function getDisetorkanAttribute()
    {
        return round($this->setor_tunai + $this->setor_qris + $this->setor_transfer);
    }

    public function getSelisihSetoranAttribute()
    {
        return round($this->disetorkan - $this->pendapatan);
    }

    public function getTabunganAttribute()
    {
        return ($this->latestByOperator() ? $this->latestByOperator()->tabungan + $this->selisih_setoran : $this->selisih_setoran + $this->operator->tabungan_awal);
    }

    public function getTanggalPanjangAttribute()
    {
        return $this->created_at->dayName . ", " . $this->created_at->format('d') . " " . $this->created_at->monthName . " " . $this->created_at->format('Y');
    }

    public function getTanggalAttribute()
    {
        return $this->created_at->format('d/m/Y');
    }
}
