<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Foreign key to users table
            $table->string('company_name')->nullable();
            $table->string('city')->nullable();
            $table->string('shop_name')->nullable();
            $table->string('website')->nullable();
            $table->text('how_you_heard')->nullable();
            $table->text('ecommerce_progress')->nullable();
            $table->text('order_management_tool')->nullable();
            $table->string('organization_size')->nullable();
            $table->string('business_model')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
