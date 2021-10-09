<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_statistics', function (Blueprint $table) {
            $table->id();
            $table->string('agent_id');
            $table->integer('layer')->default(0);
            $table->integer('level')->default(1);
            $table->string('period')->nullable();
            $table->float('current_pbv')->default(0);
            $table->float('current_gbv')->default(0);
            $table->float('acc_pvb')->default(0);
            $table->float('acc_gbv')->default(0);
            $table->string('sponser_id')->nullable();
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
        Schema::dropIfExists('agent_statistics');
    }
}
