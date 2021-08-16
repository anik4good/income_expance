<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable()->index();
            $table->bigInteger('tracking_id')->nullable();
            $table->integer('condition_amount')->nullable();
            $table->integer('condition_charge')->nullable();
            $table->integer('booking_charge')->nullable();
            $table->integer('labour_charge')->nullable();
            $table->integer('other_amount')->nullable();
            $table->integer('previous_cash')->nullable();
            $table->string('notes')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
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
        Schema::dropIfExists('incomes');
    }
}
