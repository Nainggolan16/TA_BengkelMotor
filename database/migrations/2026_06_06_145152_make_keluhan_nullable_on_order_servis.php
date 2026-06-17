<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_servis', function (Blueprint $table) {
            $table->text('keluhan')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('order_servis', function (Blueprint $table) {
            $table->text('keluhan')->nullable(false)->change();
        });
    }
};