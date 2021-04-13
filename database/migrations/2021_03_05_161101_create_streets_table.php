<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStreetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('streets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('province_code')->nullable();
            $table->string('district_code')->nullable();
            $table->string('ward_code')->nullable();
            $table->string('street_name')->nullable();
            $table->integer('status')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['province_code', 'district_code', 'ward_code', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('streets', function (Blueprint $table) {
            $table->dropIfExists('streets');
        });
    }
}
