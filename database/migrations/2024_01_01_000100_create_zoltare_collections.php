<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use MongoDB\Laravel\Schema\Blueprint;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('mongodb')->create('wallpapers', function (Blueprint $collection) {
            $collection->index('slug');
            $collection->index('uploaded_by');
            $collection->index('is_active');
            $collection->index('price');
        });

        Schema::connection('mongodb')->create('purchases', function (Blueprint $collection) {
            $collection->index('user_id');
            $collection->index('wallpaper_id');
            $collection->index('stripe_session_id');
        });

        Schema::connection('mongodb')->create('error_logs', function (Blueprint $collection) {
            $collection->index('level');
            $collection->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mongodb')->dropIfExists('wallpapers');
        Schema::connection('mongodb')->dropIfExists('purchases');
        Schema::connection('mongodb')->dropIfExists('error_logs');
    }
};

