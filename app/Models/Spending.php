<?php

namespace App\Models;

use App\Models\Operator;
use App\Models\SpendingCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Spending extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function category()
    {
        return $this->belongsTo(SpendingCategory::class, 'spending_category_id');
    }

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }
}
