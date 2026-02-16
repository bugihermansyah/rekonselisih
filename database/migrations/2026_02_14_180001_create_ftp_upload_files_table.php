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
        Schema::create('ftp_upload_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ftp_upload_id')->constrained('ftp_uploads')->cascadeOnDelete();
            $table->string('filename_original');
            $table->string('filename_ftp')->nullable();
            $table->string('status')->default('PENDING'); // PENDING, PROCESSING, SUCCESS, FAILED
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ftp_upload_files');
    }
};
