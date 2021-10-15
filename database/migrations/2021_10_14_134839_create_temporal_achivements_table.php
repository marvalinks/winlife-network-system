<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemporalAchivementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temporal_achivements', function (Blueprint $table) {
            $table->id();
            $table->string('member_id')->nullable();
            $table->string('name')->nullable();
            $table->string('period')->nullable();
            $table->float('total_pv')->default(0);
            $table->string('country')->nullable();
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
        Schema::dropIfExists('temporal_achivements');
    }
}
