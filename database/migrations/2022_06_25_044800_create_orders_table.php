<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code')->index();
            $table->uuid('user_id')->index();
            $table->uuid('pigeon_id')->index();
            $table->double('distance');
            $table->dateTime('deadline');
            $table->decimal('cost_per_km', 19, 2);
            $table->decimal('total_cost', 19, 2);
            $table->enum('status', ['pending', 'in_progress', 'arrived', 'completed', 'canceled'])->default('pending')->index();
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('CASCADE');

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
        Schema::dropIfExists('orders');
    }
}
