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
        Schema::create('sti', function (Blueprint $table) {
            $table->id();
            $table->string('card_type')->nullable();
            $table->string('terminal_id')->nullable();
            $table->string('terminal')->nullable();
            $table->string('card_no')->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->decimal('balance', 15, 2)->nullable();
            $table->datetime('trans_date')->nullable();
            $table->string('ftp_file')->nullable();
            $table->date('created_date')->nullable();
            $table->date('settle_date')->nullable();
            $table->string('response')->nullable();
            $table->string('filename')->nullable();
            $table->string('bank_mid')->nullable();
            $table->string('bank_tid')->nullable();
            $table->text('other')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sti');
    }
};
