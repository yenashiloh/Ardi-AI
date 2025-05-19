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
        Schema::connection('mongodb')->create('audit_log', function (Blueprint $collection) {
            $collection->index('user_id');
            $collection->string('action');
            $collection->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::connection('mongodb')->dropIfExists('audit_log');
    }
};
