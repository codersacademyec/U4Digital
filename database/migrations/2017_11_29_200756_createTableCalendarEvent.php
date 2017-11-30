<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCalendarEvent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',255);
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('post_types')->insert(
            ['name' => 'Text', 'created_at' => date('Y-m-d'), 'updated_at' => date('Y-m-d')]
        );

        DB::table('post_types')->insert(
            ['name' => 'Image', 'created_at' => date('Y-m-d'), 'updated_at' => date('Y-m-d')]
        );

        DB::table('post_types')->insert(
            ['name' => 'Video', 'created_at' => date('Y-m-d'), 'updated_at' => date('Y-m-d')]
        );

        Schema::create('calendar_events', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->unsigned();
            $table->integer('post_types_id')->unsigned();
            $table->string('title',255);
            $table->dateTime('start');
            $table->dateTime('end');
            $table->dateTime('approved')->nullable();
            $table->string('path_media',255)->nullable();
            $table->text('text_post')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('post_types_id')->references('id')->on('post_types');
        });

        Schema::create('calendar_event_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('calendar_events_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->text('comments');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calendar_event_comments');
        Schema::dropIfExists('calendar_events');
        Schema::dropIfExists('post_types');
    }
}
