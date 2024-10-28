<?php

namespace App\Models;

use App\Models\Barang;
use App\Models\TransaksiKeluar;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kategori extends Model
{
    protected $fillable = [
        'name',
    ];
    public function barang()
    {
        return $this->hasMany(Barang::class);
    }
    public function transaksikeluar()
    {
        return $this->hasMany(TransaksiKeluar::class);
    }
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
