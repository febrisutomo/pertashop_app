<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Shop;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $appends = ['first_name', 'nama'];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function investments()
    {
        return $this->belongsToMany(Shop::class, 'investor_shop')->withPivot(['id', 'investasi', 'no_rekening', 'pemilik_rekening', 'nama_bank']);
    }


    //getFirstName
    public function getFirstNameAttribute()
    {
        return explode(' ', $this->name)[0];
    }

    public function getNamaAttribute()
    {
        // Bagi alamat email berdasarkan _ (garis bawah), - (strip), atau @ (tanda at)
        $parts = preg_split('/[_\-@]/', $this->email);

        // Ambil bagian pertama sebagai nama pengguna
        return ucfirst($parts[0]);
    }
}
