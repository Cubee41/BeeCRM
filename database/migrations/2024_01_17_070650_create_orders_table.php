<?php

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Wrapper;
use App\Models\Customer;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Customer::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->constrained();
            $table->integer('amount'); //C'est le total
            $table->string('mode');    // A distance / Physique
            $table->string('status');
            $table->unsignedBigInteger('montant_recu');
            $table->unsignedBigInteger('reste');
            $table->boolean('reglement')->default(false);
            $table->timestamps();
        });

        Schema::create('order_product', function(Blueprint $table){
            $table->foreignIdFor(Product::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Order::class)->constrained()->cascadeOnDelete();
            $table->primary(['product_id', 'order_id']);
            $table->integer('quantity_detail');
            $table->integer('amount_detail');
            // $table->foreignIdFor(Wrapper::class)->constrained()->cascadeOnDelete()->nullable();
            // $table->integer('quantity_wrapper');
            // $table->integer('amount_wrapper');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_product');
        Schema::dropIfExists('orders');

    }
};
