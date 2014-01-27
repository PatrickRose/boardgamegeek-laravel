<?php

use PatrickRose\BoardGameGeek\BoardGame;

class TestBoardGame extends PHPUnit_Framework_TestCase {

  protected function setUp() {

    $this->gameID = 13291;

  }

  protected function getTestAttributes() {
    return array(
      'yearpublished' => 2004,
      'minplayers'    => 2,
      'maxplayers'    => 8,
      'playingtime'   => 60,
      'name'          => 'Citadels:  The Dark City',
      'description'   => 'An expansion for the German edition of the game. It included all the elements featured in the US and the 2nd French edition (new characters and districts), and some more stuff.<br/><br/>The Quarry allows you to build districts identical with districts you already have. The Imperial Treasury and the Fountain of Youth give you point bonuses at the end of the game. A bonus equal to the gold you own for the Imperial Treasury, a bonus equal to the number of purple buildings in your city for the Wish Fountain.<br/><br/>This expansion is now provided with the 3rd Edition of Citadels.<br/><br/>',
      'image'         => 'http://cf.geekdo-images.com/images/pic73710.jpg',
    );
  }

  protected function getTestGame() {
    $game = new BoardGame;
    $game->yearpublished = 2004;
    $game->minplayers = 2;
    $game->maxplayers = 8;
    $game->playingtime = 60;
    $game->name = 'Citadels:  The Dark City';
    $game->description = 'An expansion for the German edition of the game. It included all the elements featured in the US and the 2nd French edition (new characters and districts), and some more stuff.\n\nThe Quarry allows you to build districts identical with districts you already have. The Imperial Treasury and the Fountain of Youth give you point bonuses at the end of the game. A bonus equal to the gold you own for the Imperial Treasury, a bonus equal to the number of purple buildings in your city for the Wish Fountain.\n\nThis expansion is now provided with the 3rd Edition of Citadels.\n\n';
    $game->image = 'http://cf.geekdo-images.com/images/pic73710.jpg';
    return $game;
  }



  public function testSettingAttributes() {
    $game = new BoardGame;
    foreach($this->getTestAttributes() as $attribute => $value) {
      $game->$attribute = $value;
    }
    $this->assertEquals($this->getTestGame(), $game);
  }


  public function testCreatingABoardGame() {
    $game = new BoardGame($this->getTestAttributes());
    $this->assertEquals($game, $this->getTestGame());
  }

  public function testCreate() {
    $game = new BoardGame;
    $this->assertEquals($game->create($this->getTestAttributes()), $this->getTestGame());
  }
}

?>
