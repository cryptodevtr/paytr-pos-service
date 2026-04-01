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
        Schema::create('pos_rates', function (Blueprint $table) {
            $table->id();
            $table->string('pos_name');
            $table->enum('card_type', ['credit', 'debit', 'unknown']);
            $table->string('card_brand');
            $table->integer('installment');
            $table->string('currency', 3);
            $table->decimal('commission_rate', 5, 4);
            $table->unique(['pos_name', 'card_type', 'card_brand', 'installment', 'currency'], 'unique_pos_rate');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pos_rates');
    }
};
