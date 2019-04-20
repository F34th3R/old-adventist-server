<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvertisementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advertisements', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('code', 16)->unique();
            $table->string('parent_code');
            $table->unsignedInteger('department_id');
            $table->string('publicationDate');
            $table->string('eventDate')->nullable();
            $table->text('description');
            $table->string('fragment');
            $table->boolean('published')->default('0');
            $table->unsignedInteger('image_id');
            $table->string('time')->nullable();
            $table->string('place')->nullable();
            $table->string('guest')->nullable();
            $table->timestamps();

            $table->foreign('image_id')->references('id')->on('images')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('advertisements');
    }
}
