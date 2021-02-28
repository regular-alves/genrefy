<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpotifyUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spotify_users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('spotify_id')->nullable();
            $table->text('authorization');
            $table->text('token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->timestamps();

            $table->index(['spotify_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spotify_users');
    }
}
