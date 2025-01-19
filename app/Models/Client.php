<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'nom_et_prenom',
        'telephone',
        'email',
        'wilaya',
        'commune',
        'adresse',
    ];
}