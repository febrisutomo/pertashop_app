<?php

namespace App\Models;

use App\Models\Operator;
use App\Models\Corporation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shop extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function operators() {
        return $this->hasMany(Operator::class);
    }

    public function corporation() {
        return $this->belongsTo(Corporation::class);
    }
}
