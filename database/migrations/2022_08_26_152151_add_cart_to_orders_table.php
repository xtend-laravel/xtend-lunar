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
        Schema::table($this->prefix.'orders', function (Blueprint $table) {
            $table->foreignId('cart_id')->after('id')->nullable()->constrained($this->prefix.'carts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->prefix.'orders', function (Blueprint $table) {
            $table->dropForeign($this->prefix.'orders_cart_id_foreign');
            $table->dropColumn('cart_id');
        });
    }
};
