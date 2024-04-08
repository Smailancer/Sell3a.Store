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
        Schema::create('delivery_desks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_company_id')->constrained()->onDelete('cascade');
            $table->foreignId('wilaya_id')->constrained('wilayas')->onDelete('cascade');
            $table->foreignId('commune_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('daira_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('price', 8, 2)->nullable();
            $table->string('phone1');
            $table->string('phone2')->nullable();
            $table->text('address')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_desks');
    }
};
