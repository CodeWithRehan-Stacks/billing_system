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
        Schema::create('fee_invoice_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');                          // Template label
            $table->string('school_name');                   // School name on receipt
            $table->string('logo')->nullable();              // Logo file path
            $table->text('header_text')->nullable();         // Header text/tagline
            $table->text('footer_text')->nullable();         // Footer disclaimer
            $table->string('primary_color')->nullable();     // Hex color
            $table->string('font_family')->nullable();       // Font family
            $table->boolean('show_logo')->default(true);
            $table->boolean('show_signature')->default(true);
            $table->boolean('show_qr_code')->default(false);
            $table->text('terms_conditions')->nullable();
            $table->boolean('is_default')->default(false);  // Only one can be default
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_invoice_templates');
    }
};

