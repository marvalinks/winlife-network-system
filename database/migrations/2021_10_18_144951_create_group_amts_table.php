<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupAmtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_amts', function (Blueprint $table) {
            $table->id();
            $table->string('member_id');
            $table->boolean('group1')->default(0);
            $table->boolean('group2')->default(0);
            $table->boolean('group3')->default(0);
            $table->integer('level')->default(0);
            $table->integer('cl')->default(0);
            $table->integer('bl')->default(0);
            $table->integer('cl2')->default(0);
            $table->integer('bl2')->default(0);
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
        Schema::dropIfExists('group_amts');
    }
}
