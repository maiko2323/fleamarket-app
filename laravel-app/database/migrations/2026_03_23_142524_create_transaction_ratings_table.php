<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_ratings', function (Blueprint $table) {
            $table->id('transaction_ratings_id');
            $table->unsignedBigInteger('sold_item_id');
            $table->unsignedBigInteger('rater_id');
            $table->unsignedBigInteger('rated_user_id');
            $table->unsignedTinyInteger('score');
            $table->timestamps();

            $table->foreign('sold_item_id')->references('id')->on('sold_items')->onDelete('cascade');
            $table->foreign('rater_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('rated_user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unique(['sold_item_id', 'rater_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_ratings');
    }
}
