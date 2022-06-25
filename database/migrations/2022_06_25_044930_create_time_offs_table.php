<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeoffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timeoffs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('pigeon_id')->index();
            $table->enum('reason', ['sick_leave', 'rest', 'other'])->default('other');
            $table->text('desription')->nullable();
            $table->dateTime('start');
            $table->dateTime('end');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('pigeon_id')
                ->references('id')
                ->on('pigeons')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('time_offs');
    }
}
