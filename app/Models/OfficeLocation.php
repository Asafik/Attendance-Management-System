<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeLocation extends Model
{
    use HasFactory;

    // Nama tabelnya (pastikan sama dengan migration)
    protected $table = 'office_locations';

    // Kolom yang boleh diisi lewat API/Controller
    protected $fillable = [
        'name',
        'loc_code',
    ];
}
