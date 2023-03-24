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

            $table->after('user_id', function (Blueprint $table) {
                $table->foreignId('billing_address_id')->nullable()->constrained($this->prefix.'addresses');
                $table->foreignId('shipping_address_id')->nullable()->constrained($this->prefix.'addresses');
            });

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
            $table->dropForeign('Lunar_orders_billing_address_id_foreign');
            $table->dropForeign('Lunar_orders_shipping_address_id_foreign');

            $table->dropColumn('billing_address_id');
            $table->dropColumn('shipping_address_id');
        });
    }
};
