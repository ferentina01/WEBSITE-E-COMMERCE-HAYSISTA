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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
          
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->double('subtotal', 15, 2);
            $table->double('shipping', 15, 2);
            $table->string('coupon_code')->nullable();
            $table->double('discount', 15, 2)->nullable();
            $table->double('grand_total', 15, 2);


            // User Adderess related columns
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('mobile');
            $table->foreignId('province_id')->constrained()->onDelete('cascade');
            $table->text('address');
            $table->string('apartment')->nullable();
            $table->string('city');
            $table->string('subdistrict');
            $table->string('zip');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
