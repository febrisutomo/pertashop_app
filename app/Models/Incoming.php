<?php

namespace App\Models;

use App\Models\Shop;
use App\Models\User;
use App\Models\Operator;
use App\Models\Purchase;
use App\Models\DailyReport;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Incoming extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = ['penerimaan_real', 'tanggal'];

    public function report()
    {
        return $this->belongsTo(DailyReport::class, 'daily_report_id');
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function getPenerimaanRealAttribute()
    {
        return round(($this->stik_setelah_curah - $this->stik_sebelum_curah) * $this->report->shop->skala, 2);
    }

    public function getStokSetelahCurahAttribute()
    {

        return $this->stik_setelah_curah * $this->report->shop->skala;
    }

    public function getStokSebelumCurahAttribute()
    {
        return $this->stik_sebelum_curah * $this->report->shop->skala;
    }

    public function getTanggalAttribute()
    {
        return $this->created_at->format('d') . " " . $this->created_at->monthName . " " . $this->created_at->format('Y');
    }
}
