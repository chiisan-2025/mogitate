<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // 購入者
           $table->foreignId('item_id')->constrained('items')->cascadeOnDelete(); // 購入対象（1商品=1注文）
            $table->string('postal_code', 8);
            $table->string('address');
            $table->string('building')->nullable();
            $table->string('payment_method')->nullable(); // 仕様が固まるまでnullableでOK
            $table->timestamps();

            $table->unique('item_id'); // 1商品1購入
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
