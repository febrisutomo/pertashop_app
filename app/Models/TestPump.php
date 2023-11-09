<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TestPump extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = ['tanggal', 'selisih'];


    public function getTanggalAttribute()
    {
        // return $this->created_at->dayName . ", " . $this->created_at->format('d') . " " . $this->created_at->monthName . " " . $this->created_at->format('Y');
        return $this->created_at->format('d/m/Y');
    }

    public function getSelisihAttribute()
    {
        return round($this->volume_aktual - $this->volume_test, 2);
    }

}
