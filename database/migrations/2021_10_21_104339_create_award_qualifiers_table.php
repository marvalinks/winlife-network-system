<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAwardQualifiersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('award_qualifiers', function (Blueprint $table) {
            $table->id();
            $table->string('award_id');
            $table->string('member_id');
            $table->boolean('collected')->default(0);
            $table->float('used_bv')->default(0);
            $table->integer('period')->nullable();
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
        Schema::dropIfExists('award_qualifiers');
    }
}
