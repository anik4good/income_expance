<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpansesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expanses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable()->index();
            $table->bigInteger('tracking_id')->nullable();;
            $table->integer('condition_delivery')->nullable();
            $table->integer('condition_advance_payment')->nullable();
            $table->integer('tt_delivery')->nullable();
            $table->integer('dd_delivery')->nullable();
            $table->integer('ho_payment')->nullable();
            $table->integer('advance_rn')->nullable();
            $table->integer('loan_rn')->nullable();
            $table->integer('commission')->nullable();
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
        Schema::dropIfExists('expanses');
    }
}
