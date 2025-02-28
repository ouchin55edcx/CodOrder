<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $table = 'admins';

    protected $fillable = [
        'user_id',
        'client_count',
        'brand_count',
        'supplier_count'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->hasOne(Company::class);
    }
}
