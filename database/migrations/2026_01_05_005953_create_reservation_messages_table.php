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
    Schema::create('reservation_messages', function (Blueprint $table) {
        $table->id();

        $table->unsignedBigInteger('reservation_id');

        $table->foreign('reservation_id')
              ->references('idreservation') 
              ->on('reservation')           
              ->onDelete('cascade');
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

        $table->text('message');
        $table->boolean('is_read')->default(false);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_messages');
    }
};