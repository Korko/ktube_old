<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Korko\kTube\Site;

class AddSiteProvider extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sites', function ($table) {
            $table->string('provider')->unique();
            $table->dropUnique('sites_name_unique');
        });


        Site::where('name', 'google')->update(['name' => 'Youtube', 'provider' => 'google']);
        Site::where('name', 'facebook')->update(['name' => 'Facebook', 'provider' => 'facebook']);
        Site::where('name', 'vimeo')->update(['name' => 'Vimeo', 'provider' => 'vimeo']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sites', function ($table) {
            $table->dropColumn('provider');
            $table->unique('name');
        });
    }
}
