<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paramedis extends Model
{
    protected $fillable = [
        'name', 'spesialis', 'telp', 'status'
    ];
}
