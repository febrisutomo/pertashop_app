<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function previous()
    {
        return $this->where('created_at', '<', $this->created_at)->latest()->first();
    }

    public function getNaikTurunHargaJualAttribute()
    {
        //this harga jual - prev harga jual
        return abs($this->harga_jual - $this->previous()?->harga_jual ?? 0);
    }

    public function getMarginAttribute()
    {
        //this harga jual - harga beli
        return $this->harga_jual - $this->harga_beli;
    }

    public function getNaikTurunMarginAttribute()
    {
        //this margin - prev margin
        return $this->margin - $this->previous()?->margin ?? 0;
    }

    public function getTanggalAttribute()
    {
        return $this->created_at->format('d') . " " . $this->created_at->monthName . " " . $this->created_at->format('Y');
    }

    public function getPrevHargaJualAttribute()
    {
        return $this->previous()?->harga_jual ?? 0;
    }
}
