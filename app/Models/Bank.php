<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * Bank hasMany Employee
     */
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
