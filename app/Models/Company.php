<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'company_name',
        'city',
        'shop_name',
        'website',
        'how_you_heard',
        'ecommerce_progress',
        'order_management_tool',
        'organization_size',
        'business_model'
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
