<?php

namespace App\Models;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RekapModal extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = ['modal_awal', 'modal_akhir', 'penambahan_modal'];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    protected function latestByShop()
    {
        return $this->where('shop_id', $this->shop->id)->where('created_at', '<', $this->created_at)->latest()->first();
    }

    public function getModalAwalAttribute()
    {
        return $this->latestByShop() ? $this->latestByShop()->modal_akhir : $this->shop->modal_awal;
    }

    public function getModalAkhirAttribute()
    {

        return $this->modal_awal + $this->penambahan_modal;
    }

    public function getBulanAttribute()
    {
        return $this->created_at->monthName . " " . $this->created_at->format('Y');
    }

    public function getUangDiBankAttribute()
    {
        return $this->modal_awal - $this->sisa_do - $this->kas_kecil - $this->belum_disetor - $this->piutang;
    }

    public function getPenambahanModalAttribute()
    {
        return  $this->alokasi_keuntungan + $this->bunga_bank - $this->rugi - $this->pajak_bank;
    }
}
