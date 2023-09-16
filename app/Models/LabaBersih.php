<?php

namespace App\Models;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LabaBersih extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = ['laba_bersih', 'alokasi_modal', 'laba_bersih_dibagi', 'bulan'];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function getLabaBersihAttribute()
    {
        return $this->laba_kotor - $this->total_biaya;
    }

    public function getAlokasiModalAttribute()
    {
        return round($this->laba_bersih * $this->persentase_alokasi_modal / 100);
    }

    public function getLabaBersihDibagiAttribute()
    {
        return $this->laba_bersih - $this->alokasi_modal;
    }

    //getter bulan
    public function getBulanAttribute()
    {
        return $this->created_at->monthName . " " . $this->created_at->format('Y');
    }
}
