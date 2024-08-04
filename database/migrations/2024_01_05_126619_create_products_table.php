<?php

use App\Models\Groupe;
use App\Models\Category;
use App\Models\Subcategory;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('available_quantity');
            $table->integer('unit_price'); //Le prix unitaire du produit
            $table->integer('stop_loss');
            $table->integer('buying_price');

            $table->foreignIdFor(Category::class)->constrained();
            $table->foreignIdFor(Subcategory::class)->constrained();
            $table->foreignIdFor(Groupe::class)->constrained()->default(1);
            $table->timestamps();

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');

        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeignIdFor(Category::class);
        });
        Schema::table('subcategories', function (Blueprint $table) {
            $table->dropForeignIdFor(Subcategory::class);
        });
        Schema::table('groupes', function (Blueprint $table) {
            $table->dropForeignIdFor(Groupe::class);
        });
    }
};
