<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Exceptions\ResourceNotFoundException;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'phone',
        'email',
        'state',
        'city',
        'address',
    ];

    public static function findOrFail($id)
    {
        $client = self::find($id);
        if (!$client) {
            throw new ResourceNotFoundException('Client not found');
        }
        return $client;
    }
}
