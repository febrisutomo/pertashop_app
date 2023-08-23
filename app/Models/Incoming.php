<?php

namespace App\Models;

use App\Models\Operator;
use App\Models\Purchase;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Incoming extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = ['tanggal'];

    public function getTanggalAttribute()
    {
        return Carbon::parse($this->created_at)->format('d/m/Y H:i');
    }

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
}
