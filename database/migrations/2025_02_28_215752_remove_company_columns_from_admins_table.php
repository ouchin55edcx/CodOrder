<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn([
                'company_name',
                'city',
                'shop_name',
                'website',
                'how_you_heard',
                'ecommerce_progress',
                'order_management_tool',
                'organization_size',
                'business_model'
            ]);
        });
    }

    public function down()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->string('company_name');
            $table->string('city');
            $table->string('shop_name');
            $table->string('website')->nullable();
            $table->text('how_you_heard');
            $table->text('ecommerce_progress');
            $table->text('order_management_tool');
            $table->string('organization_size');
            $table->string('business_model');
        });
    }
};
