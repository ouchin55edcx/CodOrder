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
        Schema::table('clients', function (Blueprint $table) {
            // Add the company_id column
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            // Drop the company_id column
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
        });
    }
};
