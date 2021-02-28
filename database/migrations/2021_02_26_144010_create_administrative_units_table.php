<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdministrativeUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('administrative_units', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('l1_name')->nullable();
            $table->string('l1_code')->nullable();
            $table->string('l2_name')->nullable();
            $table->string('l2_code')->nullable();
            $table->string('l3_name')->nullable();
            $table->string('l3_code')->nullable();
            $table->string('level')->nullable();
            $table->string('en_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('administrative_units');
    }
}
