<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends User
{
    protected $table = 'admins'; 

    protected $fillable = [
        'user_id',
        'company_name',
        'city',
        'shop_name',
        'website',
        'how_you_heard',
        'ecommerce_progress',
        'order_management_tool',
        'organization_size',
        'business_model',
        'client_count',
        'brand_count',
        'supplier_count'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
