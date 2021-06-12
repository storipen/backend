<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartStoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('part_stories', function (Blueprint $table) {
            $table->id();
            $table->string('idstory');
            $table->string('titlePart');
            $table->string('thumbnail');
            $table->string('imageHeader');
            $table->text('content');
            $table->integer('countView');
            $table->boolean('active');
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
        Schema::dropIfExists('part_stories');
    }
}
