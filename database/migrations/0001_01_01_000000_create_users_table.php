<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use MongoDB\Laravel\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('mongodb')->create('users', function (Blueprint $collection) {
            $collection->unique('email');
            $collection->index('created_at');
        });

        Schema::connection('mongodb')->create('password_reset_tokens', function (Blueprint $collection) {
            $collection->primary('email');
            $collection->index('created_at');
        });

        Schema::connection('mongodb')->create('sessions', function (Blueprint $collection) {
            $collection->index('user_id');
            $collection->index('last_activity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mongodb')->dropIfExists('users');
        Schema::connection('mongodb')->dropIfExists('password_reset_tokens');
        Schema::connection('mongodb')->dropIfExists('sessions');
    }
};
