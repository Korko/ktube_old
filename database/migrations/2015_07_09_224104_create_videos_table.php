<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('video_id');
            $table->string('name');
            $table->string('thumbnail')->nullable();
            $table->integer('channel_id')->unsigned();
            $table->timestamp('published_at');

            $table->foreign('channel_id')->references('id')->on('channels')->onDelete('cascade');
            $table->unique(['channel_id', 'video_id']);
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('videos');
    }
}
