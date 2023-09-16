<?php

namespace App\Models;

use App\Models\InvestorShop;
use App\Models\ProfitSharing;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvestorProfit extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function investorShop()
    {
        return $this->belongsTo(InvestorShop::class);
    }

    public function profitSharing()
    {
        return $this->belongsTo(ProfitSharing::class);
    }
}
