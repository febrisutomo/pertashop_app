<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = ['harga', 'diterima', 'sisa'];

    public function incomings()
    {
        return $this->hasMany(Incoming::class);
    }

    public function getHargaAttribute()
    {
        return $this->total_bayar / $this->volume;
    }

    public function getSisaAttribute()
    {
        return $this->volume - $this->incomings->sum('volume');
    }

    public function getDiterimaAttribute()
    {
        return $this->volume - $this->sisa;
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
