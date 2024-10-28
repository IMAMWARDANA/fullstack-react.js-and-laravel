<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class role extends Model
{
    protected $fillable = [
        'name',
    ];
    public function users()
    {
        return $this->hasMany(User::class);
    }
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
