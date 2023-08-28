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

    protected $guarded = ['id'];

    protected $appends = ['tanggal', 'volume', 'rupiah'];

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


    public function getVolumeAttribute()
    {

        return round($this->totalisator_akhir - $this->totalisator_awal, 2);
    }

    public function getRupiahAttribute()
    {
        return  $this->volume * $this->price->harga_jual;
    }
}
