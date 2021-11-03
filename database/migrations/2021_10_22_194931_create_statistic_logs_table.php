<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatisticLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistic_logs', function (Blueprint $table) {
            $table->id();
            $table->string('member_id');
            $table->integer('level')->default(1);
            $table->integer('period')->nullable();
            $table->float('current_pbv')->default(0);
            $table->float('current_gbv')->default(0);
            $table->float('acc_pvb')->default(0);
            $table->float('acc_gbv')->default(0);
            $table->string('sponser_id')->nullable();
            $table->float('owe_bl')->default(0);
            $table->float('paid_bl')->default(0);
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
        Schema::dropIfExists('statistic_logs');
    }
}
