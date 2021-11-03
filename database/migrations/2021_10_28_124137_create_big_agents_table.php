<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBigAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('big_agents', function (Blueprint $table) {
            $table->id();
            $table->string('member_id');
            $table->string('firstname');
            $table->string('lastname');
            $table->integer('period')->nullable();
            $table->string('level')->nullable();
            $table->boolean('active')->default(1);
            $table->string('sponser_id')->nullable();
            $table->string('parent_id')->nullable();
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
        Schema::dropIfExists('big_agents');
    }
}
