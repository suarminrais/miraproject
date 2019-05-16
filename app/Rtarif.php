<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rtarif extends Model
{
    protected $fillable=[
        'nama', 'kelas', 'kategori', 'nokamar', 'nott' ,'tarif'
    ];
}
