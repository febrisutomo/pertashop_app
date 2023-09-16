<?php

namespace App\Models;

use App\Models\DailyReport;
use App\Models\SpendingCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Spending extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function category()
    {
        return $this->belongsTo(SpendingCategory::class, 'category_id');
    }

    public function dailyReport()
    {
        return $this->belongsTo(DailyReport::class);
    }
}
