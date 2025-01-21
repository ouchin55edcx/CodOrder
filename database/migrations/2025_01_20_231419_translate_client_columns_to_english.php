<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TranslateClientColumnsToEnglish extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->renameColumn('nom_et_prenom', 'full_name');
            $table->renameColumn('telephone', 'phone');
            $table->renameColumn('wilaya', 'state');
            $table->renameColumn('commune', 'city');
            $table->renameColumn('adresse', 'address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->renameColumn('full_name', 'nom_et_prenom');
            $table->renameColumn('phone', 'telephone');
            $table->renameColumn('state', 'wilaya');
            $table->renameColumn('city', 'commune');
            $table->renameColumn('address', 'adresse');
        });
    }
}
