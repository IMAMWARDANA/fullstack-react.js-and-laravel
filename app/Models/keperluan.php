<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class keperluan extends Model
{
    protected $fillable = [
        'name',
    ];
    public function stokbks()
    {
        return $this->hasMany(StokBK::class);
    }
    public function transaksikeluars()
    {
        return $this->hasMany(TransaksiKeluar::class);
    }
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
