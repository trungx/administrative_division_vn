<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('units', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->nullable();
            $table->string('parent_code')->nullable();
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->integer('level')->nullable();
            $table->string('slug')->nullable();
            $table->string('name_with_type')->nullable();
            $table->string('path')->nullable();
            $table->string('path_with_type')->nullable();
            $table->integer('status')->default(1)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('units');
    }
}
