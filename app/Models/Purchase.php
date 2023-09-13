<?php

namespace App\Models;

use App\Models\Incoming;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $append = ['harga_per_liter', 'tanggal', 'status'];

    public function incoming()
    {
        return $this->hasOne(Incoming::class);
    }

    public function getHargaPerLiterAttribute()
    {
        return $this->total_bayar / $this->volume;
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function getTanggalAttribute()
    {
        return $this->created_at->format('d') . " " . $this->created_at->monthName . " " . $this->created_at->format('Y');
    }

    public function getStatusAttribute()
    {
        if ($this->incoming) {
            return "Diterima";
        } else {
            return "Dipesan";
        }
    }
}
