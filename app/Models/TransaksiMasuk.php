<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiMasuk extends Model
{
    protected $fillable = [
        'transaksi_keluar_id',
        'alasan',
        'tanggalinput',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function transaksikeluar()
    {
        return $this->belongsTo(TransaksiKeluar::class, 'transaksi_keluar_id');
    }
    public function transaksimasukdetails()
    {
        return $this->hasMany(transaksimasukdetail::class, 'transaksi_masuk_id');

    }
}
