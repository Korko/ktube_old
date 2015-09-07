<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Korko\kTube\Site;
use Korko\kTube\Channel;
use Korko\kTube\Video;

class AddVideoThumbnail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('videos', function ($table) {
            $table->string('thumbnail')->nullable();
        });


        Video::fromSite('google')
          ->update(['thumbnail' => new Expression('CONCAT("http://img.youtube.com/vi/", video_id, "/mqdefault.jpg")')]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('videos', function ($table) {
            $table->dropColumn('thumbnail');
        });
    }
}
