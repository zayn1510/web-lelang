<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tbl_syarat_berkas_kkn', function (Blueprint $table) {
            $table->id();
            $table->string('name_berkas',100)->nullable(true);
            $table->string('title_berkas',100)->nullable(true);
            $table->string('tipe_berkas',30)->nullable(true);
            $table->integer("periode_kkn")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_syarat_berkas_kkn');
    }
};
