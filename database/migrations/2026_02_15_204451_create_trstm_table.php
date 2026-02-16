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
        Schema::create('trstm', function (Blueprint $table) {
            $table->id();
            $table->string('trs_id')->nullable();
            $table->datetime('trstm_date')->nullable();
            $table->string('emoney_type')->nullable(); // Assuming string as it might be a code
            $table->string('card_no')->nullable();
            $table->string('mid')->nullable();
            $table->string('terminal_id')->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->decimal('balance', 15, 2)->nullable();
            $table->text('log')->nullable(); // Using text for 1024 length compatibility/flexibility, or string('log', 1024)
            $table->text('inv')->nullable(); // Using text or string 1024
            $table->integer('counter')->nullable();
            $table->string('bank_type')->nullable();
            $table->string('user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trstm');
    }
};
