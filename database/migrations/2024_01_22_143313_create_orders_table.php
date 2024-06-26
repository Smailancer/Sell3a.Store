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
            $table->string('number');
            $table->enum('status', ['new', 'processing', 'shipped', 'delivered', 'cancelled'])->default('new');
            $table->string('name');
            $table->string('tel');
            $table->foreignId('wilaya_id')->constrained('wilayas')->onDelete('cascade');
            $table->foreignId('commune_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('daira_id')->nullable()->constrained()->onDelete('set null');
            $table->string('adresse');
            $table->decimal('total_price', 8, 2);
            $table->string('note');

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
