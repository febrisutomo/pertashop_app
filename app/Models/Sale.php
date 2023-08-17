<?php

namespace App\Models;

use App\Models\Shop;
use App\Models\User;
use App\Models\Price;
use App\Models\Operator;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = ['operator_id', 'totalisator_akhir', 'stik_akhir', 'harga', 'losses_gain'];

    protected $appends = ['tanggal', 'jumlah', 'omset'];

    public function price()
    {
        return $this->belongsTo(Price::class);
    }

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function getTanggalAttribute()
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('d/m/Y H:i');
    }


    // public function previousSale()
    // {
    //     return $this->where('created_at', '<', $this->created_at)->latest()->first();
    // }

    // public function getTotalisatorAwalAttribute()
    // {
    //     $previousSale = $this->getPreviousSale();
    //     return $previousSale ?  $previousSale->totalisator_akhir : $this->shop;
    // }

    public function getJumlahAttribute()
    {

        return round($this->totalisator_akhir - $this->totalisator_awal, 3);
    }

    // public function getLossesGainAttribute()
    // {
    //     return round((($this->stik_akhir * $this->skala - $this->totalisator_akhir) / ($this->stik_akhir * $this->skala)) * 100, 3);
    // }

    public function getOmsetAttribute()
    {
        return  $this->jumlah * $this->price->harga_jual;
    }
}
