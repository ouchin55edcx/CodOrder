<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'phone',
        'email',
        'city',
        'address',
    ];

    /**
     * Get the companies that the client belongs to.
     */
    public function companies()
    {
        return $this->belongsToMany(Company::class, 'client_company');
    }

}
