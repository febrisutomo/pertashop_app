<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvestorShop extends Pivot
{
  use HasFactory;

  public function profits()
  {
    return $this->hasMany(InvestorProfit::class, 'investor_shop_id');
  }
}
