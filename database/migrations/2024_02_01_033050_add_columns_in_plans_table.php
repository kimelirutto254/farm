<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->string('trial')->default('off')->after('shipping_method');
            $table->string('trial_days')->nullable()->after('trial');
            $table->integer('is_active')->default(1)->after('description');
            $table->decimal('price', 30, 2)->nullable()->default(0.0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            //
        });
    }
};
