<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_images', function (Blueprint $table) {
            $table->id();
            $table->string('shopify_gid');
            $table->string('url');
            $table->string('alt');
            $table->unsignedInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->enum('media_content_type', ['external_video', 'image', 'video', 'model_3d']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        //
    }
};
