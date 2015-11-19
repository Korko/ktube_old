<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountPlaylist extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_playlist', function (Blueprint $table) {
            $table->integer('account_id')->unsigned();
            $table->integer('playlist_id')->unsigned();
            $table->string('playlist_site_id');

            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('playlist_id')->references('id')->on('playlists')->onDelete('cascade');
            $table->unique(['account_id', 'playlist_site_id']);
            $table->index('playlist_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('account_playlist');
    }
}
