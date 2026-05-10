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
    Schema::create('tickets', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // පාරිභෝගිකයා [cite: 25]
        $table->string('subject'); // [cite: 15]
        $table->text('description'); // [cite: 15]
        $table->string('category'); // [cite: 15]
        $table->enum('priority', ['Low', 'Medium', 'High']); // [cite: 15]
        $table->enum('status', ['Open', 'In Progress', 'Resolved'])->default('Open'); // [cite: 16]
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
