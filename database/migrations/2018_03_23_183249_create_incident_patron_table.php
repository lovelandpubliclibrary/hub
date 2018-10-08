<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncidentPatronTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incident_patron', function(Blueprint $table) {
            $table->integer('incident_id')->unsigned();
            $table->integer('patron_id')->unsigned();
            $table->timestamps();
            $table->primary(['incident_id', 'patron_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('incident_patron');
    }
}
