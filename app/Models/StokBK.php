<?php

namespace App\Models;

use App\Models\Stok;
use App\Models\Barang;
use App\Models\keperluan;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StokBK extends Model
{
    protected $fillable = [
        'barang_id',
        'stok',
        'keperluan_id',
        'keterangan',
        'tanggalkeluar',
    ];
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function stok()
    {
        return $this->belongsTo(Stok::class);
    }
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
    public function keperluan()
    {
        return $this->belongsTo(Keperluan::class);
    }
}
