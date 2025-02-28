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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('admins')->onDelete('cascade');
            $table->string('company_name');
            $table->string('city');
            $table->string('shop_name');
            $table->string('website')->nullable();
            $table->text('how_you_heard');
            $table->text('ecommerce_progress');
            $table->text('order_management_tool');
            $table->string('organization_size');
            $table->string('business_model');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
