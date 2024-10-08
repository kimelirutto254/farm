<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'plans',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name', 100)->unique();
                $table->float('price',15,2)->nullable();
                $table->string('duration', 100)->nullable();
                $table->integer('max_stores')->default(0);
                $table->float('storage_limit')->default(0);
                $table->string('enable_chatgpt')->default('off');
                $table->integer('max_users')->default(0);
                $table->string('pwa_store')->default(0);
                $table->integer('max_products')->default(0);
                $table->string('enable_custdomain')->default('off');
                $table->string('enable_custsubdomain')->default('off');
                $table->string('shipping_method')->default('off');
                $table->string('image')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plans');
    }
}
