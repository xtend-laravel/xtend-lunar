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
        Schema::table($this->prefix.'product_variants', function (Blueprint $table) {
            $table->boolean('base')->default(false)->index()->after('tax_class_id')->nullable();
            $table->boolean('primary')->default(false)->index()->after('base')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->prefix.'product_variants', function (Blueprint $table) {
            $table->dropColumn('base');
            $table->dropColumn('primary');
        });
    }
};
