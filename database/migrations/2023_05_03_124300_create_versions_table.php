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
        Schema::create('versions', function (Blueprint $table) {
            $table->id();
            $table->string('latest_ios', 5);
            $table->string('minimum_ios', 5);
            $table->string('url_ios');
            $table->string('latest_android', 5);
            $table->string('minimum_android', 5);
            $table->string('url_android');
            $table->boolean('maintenanceMode');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('versions');
    }
};
