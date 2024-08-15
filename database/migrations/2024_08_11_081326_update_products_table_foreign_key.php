<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Hapus foreign key yang ada
            $table->dropForeign(['category_id']);

            // Tambahkan foreign key baru dengan onDelete('cascade')
            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Hapus foreign key yang ada
            $table->dropForeign(['category_id']);

            // Tambahkan kembali foreign key tanpa onDelete('cascade')
            $table->foreign('category_id')
                ->references('id')
                ->on('categories');
        });
    }
};
