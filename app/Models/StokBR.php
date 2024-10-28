<?php

namespace App\Models;

use App\Models\Stok;
use App\Models\Barang;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StokBR extends Model
{
    protected $fillable = [
        'barang_id',
        'stok',
        'keterangan',
        'tanggalrusak',
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
}