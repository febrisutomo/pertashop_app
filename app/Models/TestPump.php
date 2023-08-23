<?php

namespace App\Models;

use App\Models\Shop;
use App\Models\Operator;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TestPump extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = ['jumlah', 'tanggal'];


    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }


    public function getJumlahAttribute()
    {

        return round($this->totalisator_akhir - $this->totalisator_awal, 3);
    }

    
    public function getTanggalAttribute()
    {
        return Carbon::parse($this->created_at)->format('d/m/Y H:i');
    }
}
