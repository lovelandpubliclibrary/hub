<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDivisionUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('division_user', function (Blueprint $table) {
            $table->primary(['division_id', 'user_id']);
            $table->integer('division_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->boolean('supervisor')->nullable()->default(null);
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
        Schema::dropIfExists('division_user');
    }
}
