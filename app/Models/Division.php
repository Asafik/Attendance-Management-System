<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Division extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATION (sementara dimatikan karena Employee belum dibuat)
    |--------------------------------------------------------------------------
    |
    | Nanti kalau model Employee sudah dibuat,
    | tinggal buka komentar di bawah ini.
    |
    */

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
