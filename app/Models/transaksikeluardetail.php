<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transaksikeluardetail extends Model
{
    protected $fillable = [
        'barang_id',
        'transaksi_keluar_id',
        'awalpinjam',
        'jumlah',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
    public function transaksikeluar()
    {
        return $this->belongsTo(TransaksiKeluar::class);
    }
}