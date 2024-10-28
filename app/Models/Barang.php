<?php

namespace App\Models;

use App\Models\Stok;
use App\Models\Satuan;
use App\Models\StokBK;
use App\Models\StokBM;
use App\Models\StokBR;
use App\Models\Kategori;
use App\Models\transaksikeluardetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barang extends Model
{
    protected $fillable = [
        'name',
        'kode',
        'merek',
        'stok',
        'kategori_id',
        'satuan_id'
    ];
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function stok()
    {
        return $this->belongsTo(Stok::class);
    }
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }
    public function stoks()
    {
        return $this->hasMany(Stok::class);
    }
    public function stokbm()
    {
        return $this->hasMany(StokBM::class);
    }
    public function stokbk()
    {
        return $this->hasMany(StokBK::class);
    }
    public function stokbr()
    {
        return $this->hasMany(StokBR::class);
    }
    public function transaksikeluardetails()
    {
        return $this->hasMany(transaksikeluardetail::class);
    }
    public function transaksimasukdetails()
    {
        return $this->hasMany(transaksimasukdetail::class);
    }
}