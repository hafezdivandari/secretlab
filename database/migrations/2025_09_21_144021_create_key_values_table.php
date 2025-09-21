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
        Schema::create('key_values', function (Blueprint $table) {
            $table->id();
            $table->string('key')->index();
            $table->json('value');
            $table->timestamp('created_at')->useCurrent();

            $table->index(['key', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('key_values');
    }
};
