<?php

namespace App\Models;

use App\Models\Barang;
use App\Models\StokBK;
use App\Models\StokBM;
use App\Models\StokBR;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stok extends Model
{
    protected $fillable = [
        'barang_id',
        'stokawal',
        'stokmasuk',
    ];
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function barangs()
    {
        return $this->hasMany(Barang::class);
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
}