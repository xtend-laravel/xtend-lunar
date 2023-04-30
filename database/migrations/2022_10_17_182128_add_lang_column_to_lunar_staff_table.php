<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table($this->prefix.'staff', function (Blueprint $table) {
            $table->foreignId('language_id')
                ->default(1)
                ->after('id')->constrained($this->prefix.'languages');
        });
    }

    public function down(): void
    {
        Schema::table($this->prefix.'staff', function (Blueprint $table) {
            $table->dropForeign($this->prefix.'staff_language_id_foreign');
            $table->dropColumn('language_id');
        });
    }
};