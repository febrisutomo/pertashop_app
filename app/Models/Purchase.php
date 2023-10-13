<?php

namespace App\Models;

use App\Models\Incoming;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = ['harga_per_liter', 'tanggal', 'diterima', 'sisa'];

    public function incomings()
    {
        return $this->hasMany(Incoming::class);
    }

    public function getHargaPerLiterAttribute()
    {
        return $this->total_bayar / $this->volume;
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function getTanggalAttribute()
    {
        return $this->created_at->format('d') . " " . $this->created_at->monthName . " " . $this->created_at->format('Y');
    }

    public function getDiterimaAttribute()
    {
        return $this->incomings()->sum('volume');
    }

    public function getSisaAttribute()
    {
        return $this->volume - $this->diterima;
    }
}
