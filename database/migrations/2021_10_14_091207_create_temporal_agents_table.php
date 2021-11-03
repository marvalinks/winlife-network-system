<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemporalAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temporal_agents', function (Blueprint $table) {
            $table->id();
            $table->string('member_id');
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('telephone')->nullable();
            $table->string('address')->nullable();
            $table->integer('period')->nullable();
            $table->string('sponser_id')->nullable();
            $table->string('nationality')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_no')->nullable();
            $table->string('momo_name')->nullable();
            $table->string('momo_no')->nullable();
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
        Schema::dropIfExists('temporal_agents');
    }
}
