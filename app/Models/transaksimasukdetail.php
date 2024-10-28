<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transaksimasukdetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaksi_masuk_id',
        'barang_id',
        'jumlah',
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function transaksiMasuk()
    {
        return $this->belongsTo(TransaksiMasuk::class);

    }
}
