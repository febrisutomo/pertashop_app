<?php

namespace App\Models;

use App\Models\Shop;
use App\Models\InvestorProfit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProfitSharing extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = ['roi', 'bulan', 'sisa_profit_dibagi'];

    public function investorProfits()
    {
        return $this->hasMany(InvestorProfit::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function lastByShop()
    {
        return $this->where('shop_id', $this->shop->id)->where('created_at', '<', $this->created_at)->latest()->first();
    }

    public function getRoiAttribute()
    {

        return $this->lastByShop() ? $this->lastByShop()->roi - $this->nilai_profit_sharing : $this->shop->nilai_investasi - $this->nilai_profit_sharing;
    }

    public function getBulanAttribute()
    {
        return $this->created_at->monthName . " " . $this->created_at->format('Y');
    }

    public function getSisaProfitDibagiAttribute()
    {
        return $this->nilai_profit_sharing - $this->alokasi_modal;
    }
}
