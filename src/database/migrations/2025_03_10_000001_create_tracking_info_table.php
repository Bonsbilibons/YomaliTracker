<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tracking_info', function (Blueprint $table) {
            $table->id();

            $table->char('fingerprint', 32);
            $table->string('url');
            $table->string('ip');
            $table->string('client');

            $table->unsignedBigInteger('plugin_id')->index();
            $table->foreign('plugin_id')->references('id')->on('plugins')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracking_info');
    }
};
