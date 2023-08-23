<?php

namespace App\Models;

use App\Models\Price;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = ['tanggal', 'total_harga', 'datang', 'sisa'];

    public function price()
    {
        return $this->belongsTo(Price::class);
    }

    public function incomings()
    {
        return $this->hasMany(Incoming::class);
    }

    public function getSisaAttribute()
    {
        return $this->jumlah - $this->incomings->sum('jumlah');
    }

    public function getDatangAttribute()
    {
        return $this->jumlah - $this->sisa;
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function getTanggalAttribute()
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('d/m/Y');
    }

    public function getTotalHargaAttribute()
    {
        return $this->jumlah * $this->price->harga_beli;
    }
}
