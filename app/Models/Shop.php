<?php

namespace App\Models;

use App\Models\User;
use App\Models\Corporation;
use App\Models\ProfitSharing;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shop extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = ['stok_awal'];

    public function corporation()
    {
        return $this->belongsTo(Corporation::class);
    }

    public function operators()
    {
        return $this->hasMany(User::class)->where('role', 'operator');
    }

    public function investors()
    {
        return $this->belongsToMany(User::class, 'investor_shop')->using(InvestorShop::class)->withPivot(['id', 'persentase', 'no_rekening', 'pemilik_rekening', 'nama_bank']);
    }

    public function getStokAwalAttribute()
    {

        return $this->stik_awal * $this->skala;
    }

    public function profitSharings()
    {
        return $this->hasMany(ProfitSharing::class);
    }
}
