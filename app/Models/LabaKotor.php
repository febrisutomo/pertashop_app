<?php

namespace App\Models;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LabaKotor extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = ['stock_awal_do'];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    protected function latestByShop()
    {
        return $this->where('shop_id', $this->shop_id)->where('created_at', '<', $this->created_at)->latest()->first();
    }

    public function getStockAwalDoAttribute()
    {
        return $this->latestByShop()->sisa_do ?? 0;
    }
}
