<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lunar\Base\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->prefix.'customers', function (Blueprint $table) {
            $table->foreignId('language_id')
                  ->after('id')
                  ->default(1)
                  ->constrained($this->prefix.'languages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->prefix.'customers', function (Blueprint $table) {
            $table->dropForeign($this->prefix.'customers_language_id_foreign');
            $table->dropColumn('language_id');
        });
    }
};
