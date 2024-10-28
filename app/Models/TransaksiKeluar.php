<?php

namespace App\Models;

use App\Models\Barang;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransaksiKeluar extends Model
{
    protected $fillable = [
        'notransaksi',
        'namainstansi',
        'keperluan_id',
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
    public function transaksikeluardetails()
    {
        return $this->hasMany(Transaksikeluardetail::class, 'transaksi_keluar_id');
    }
    public function keperluan()
    {
        return $this->belongsTo(keperluan::class);
    }
    public function transaksimasuk()
{
    return $this->hasMany(TransaksiMasuk::class);
}
}