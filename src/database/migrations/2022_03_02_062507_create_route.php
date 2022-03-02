<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoute extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('route', function (Blueprint $table) {
            $table->increments('id');
            $table->string('route', 50);
            $table->enum('method', [
                'get',
                'post',
                'put',
                'patch',
                'delete',
            ])
            ->default('get');
            $table->string('controller', 100);
            $table->string('alias', 20)->nullable();
            $table->json('middleware')->nullable();
            $table->enum('type', [
                'api',
                'dashboard_api',
                'web',
            ])->default('api');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('route');
    }
}
