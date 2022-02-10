<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255)->nullable()->unique();
            $table->unsignedBigInteger('parent_cat');
            $table->text('short_desc');
            $table->text('detail')->nullable();
            $table->string('slug', 255)->unique();
            $table->string('thumb')->unique()->nullable();
            $table->integer('sale_price')->unsigned()->nullable();
            $table->integer('old_price')->unsigned()->nullable();
            $table->integer('inventory')->unsigned()->nullable();
            $table->string('status');
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
        Schema::dropIfExists('product');
    }
}
