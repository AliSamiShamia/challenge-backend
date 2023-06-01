<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sources', function (Blueprint $table) {
            $table->id();
            $table->string('title');//Name of the source
            $table->string('slug');//Name of the source
            $table->string('url');//url of the api source
            $table->string('api_key');//each source has api-key
            $table->string('adapter')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * param structure {
     * page:number,
     * fq=source("name of source") And / OR news_desk:"name of desk"
     *
     * }*
     **/

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sources');
    }
};
