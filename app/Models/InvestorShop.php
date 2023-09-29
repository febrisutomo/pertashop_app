<?php

namespace App\Models;

use App\Models\Shop;
use App\Models\InvestorProfit;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvestorShop extends Pivot
{
  use HasFactory;

  public function profits()
  {
    return $this->hasMany(InvestorProfit::class, 'investor_shop_id');
  }

  public function shop()
  {
    return $this->belongsTo(Shop::class);
  }

  public function getPersentaseAttribute()
  {
    return $this->shop->nilai_investasi != 0 ? $this->investasi / $this->shop->nilai_investasi * 100 : 0;
  }
}
