<?php

use Illuminate\Database\Migrations\Migration;

class PatrickRoseBoardGameGeekCreateBggGamesTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('bgg_games', function($table) {
      $table->integer('id')->primaryKey();
      $table->integer('minplayers');
      $table->integer('maxplayers');
      $table->integer('playingtime');
      $table->integer('age');
      $table->string('name');
      $table->text('description');
    });
    //
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('bgg_games')
    //
  }

}
