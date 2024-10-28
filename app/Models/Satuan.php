<?php

namespace App\Models;

use App\Models\Barang;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Satuan extends Model
{
    protected $fillable = [
        'name',
    ];
    public function barang()
    {
        return $this->hasMany(Barang::class);
    }
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
