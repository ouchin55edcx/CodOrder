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
            // Drop the admin_id column
            $table->dropForeign(['admin_id']); // Drop foreign key constraint
            $table->dropColumn('admin_id');
        });
    }

    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            // Add the admin_id column back (optional, for rollback)
            $table->foreignId('admin_id')->constrained('admins')->onDelete('cascade');
        });
    }
};
