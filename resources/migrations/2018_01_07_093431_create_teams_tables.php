<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTeamsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Team Columns...
            $table->integer('current_team_id')->nullable();
        });

        Schema::create('team_members', function (Blueprint $table) {
            $table->integer('team_id')->index();
            $table->integer('user_id')->index();
            $table->timestamps();
            $table->primary(['team_id', 'user_id']);
        });

        Schema::create('teams', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->string('name')->index();
            $table->string('slug')->index();
            $table->text('description')->nullable();
            $table->integer('owner_id')->index();
            $table->integer('captain_id')->index();
            $table->integer('cocaptain_id')->index();
            $table->timestamps();
        });

        Schema::create('team_invitations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('team_id')->index();
            $table->integer('user_id')->nullable()->index();
            $table->string('email');
            $table->string('token', 40)->unique();
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
        Schema::dropIfExists('teams');
        Schema::dropIfExists('team_members');
    }
}