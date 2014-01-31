<?php

use PatrickRose\BoardGameGeek\BoardGameGeekAPI;
use PatrickRose\BoardGameGeek\BoardGame;
use PatrickRose\BoardGameGeek\XMLReader;

class TestBoardGameGeekAPI extends PHPUnit_Framework_TestCase {

  protected function setUp() {

    $this->gameID = 13291;
    $this->otherGame = 13297;

  }

  public function tearDown() {
    Mockery::close();
  }

  protected function getTestAttributes() {
    return array(
      'yearpublished' => '2004',
      'minplayers'    => '2',
      'maxplayers'    => '8',
      'playingtime'   => '60',
      'name'          => 'Citadels:  The Dark City',
      'description'   => 'An expansion for the German edition of the game. It included all the elements featured in the US and the 2nd French edition (new characters and districts), and some more stuff.<br/><br/>The Quarry allows you to build districts identical with districts you already have. The Imperial Treasury and the Fountain of Youth give you point bonuses at the end of the game. A bonus equal to the gold you own for the Imperial Treasury, a bonus equal to the number of purple buildings in your city for the Wish Fountain.<br/><br/>This expansion is now provided with the 3rd Edition of Citadels.<br/><br/>',
      'image'         => 'http://cf.geekdo-images.com/images/pic73710.jpg',
    );
  }

  protected function getCollection() {
    return array(
      '6249' => 'Alhambra',
      '137408' => 'Amerigo',
      '124742' => 'Android: Netrunner',
      '133500' => 'Android: Netrunner - Cyber Exodus',
      '132005' => 'Android: Netrunner - Trace Amount',
      '130806' => 'Android: Netrunner - What Lies Ahead',
    );
  }

  protected function getTestGame() {
    $game = new BoardGame;
    $game->id = '13291';
    $game->yearpublished = '2004';
    $game->minplayers = '2';
    $game->maxplayers = '8';
    $game->playingtime = '60';
    $game->name = 'Citadels:  The Dark City';
    $game->description = 'An expansion for the German edition of the game. It included all the elements featured in the US and the 2nd French edition (new characters and districts), and some more stuff.\n\nThe Quarry allows you to build districts identical with districts you already have. The Imperial Treasury and the Fountain of Youth give you point bonuses at the end of the game. A bonus equal to the gold you own for the Imperial Treasury, a bonus equal to the number of purple buildings in your city for the Wish Fountain.\n\nThis expansion is now provided with the 3rd Edition of Citadels.\n\n';
    $game->image = 'http://cf.geekdo-images.com/images/pic73710.jpg';
    return $game;
  }

  protected function getOtherGame() {
    $game = new BoardGame;
    $game->id = '13297';
    $game->yearpublished = '2004';
    $game->minplayers = '2';
    $game->maxplayers = '5';
    $game->playingtime = '45';
    $game->name = 'Ticket to Ride: Mystery Train Expansion';
    $game->description = "The Ticket to Ride: Mystery Train Expansion adds some cards to the Ticket deck. These additions aren't actual tickets, though. Instead, they are special cards that allow you to get bonus points at the end of the game, with the exception of one card that allows you to, in lieu of a turn, look through the entire deck of tickets and take any card you want.<br/><br/>One card lets you double the value of any ticket that you make (only for cards worth 10 or less), one gives you bonus points for making a cross-country route, one gives you bonus points for a west coast route and one gives you bonus points for connecting to the most cities.<br/><br/>This expansion is available as a free giveaway at Essen in October '04, and in the December '04 Game Trade Magazine. It should also be available to distributors, retailers, and through the daysofwonder web site when it's in print.<br/><br/>8 to 10 cards.<br/><br/>";
    $game->image = 'http://cf.geekdo-images.com/images/pic56050.jpg';
    return $game;
  }

  public function testGetBoardGame() {
    $mock = Mockery::mock('PatrickRose\BoardGameGeek\XMLReader');
    $mock->shouldReceive('parse')->with('http://www.boardgamegeek.com/xmlapi/boardgame/13291?versions=1')->andReturn(simplexml_load_file('tests/13291.xml'));
    $api = new BoardGameGeekApi($mock);
    $game = $api->getBoardGame($this->gameID);
    $this->assertEquals($this->getTestGame(), $game);
  }

  public function testGetMultipleGames() {
    $mock = Mockery::mock('PatrickRose\BoardGameGeek\XMLReader');
    $mock->shouldReceive('parse')->with('http://www.boardgamegeek.com/xmlapi/boardgame/13291,13297?versions=1')->andReturn(simplexml_load_file('tests/13291,13297.xml'));
    $api = new BoardGameGeekApi($mock);
    $games = $api->getBoardGame(array($this->gameID, $this->otherGame));
    $this->assertEquals(array($this->getTestGame(), $this->getOtherGame()), $games);
  }

  public function testGetNonExistantGame() {
    $mock = Mockery::mock('PatrickRose\BoardGameGeek\XMLReader');
    $mock->shouldReceive('parse')->with('http://www.boardgamegeek.com/xmlapi/boardgame/132911111111?versions=1')->andReturn(simplexml_load_file('tests/132911111111.xml'));
    $api = new BoardGameGeekApi($mock);
    $game = $api->getBoardGame(132911111111);
    $this->assertEquals(null, $game);
  }

  public function testGetMultipleNonExistantGames() {
    $mock = Mockery::mock('PatrickRose\BoardGameGeek\XMLReader');
    $mock->shouldReceive('parse')->with('http://www.boardgamegeek.com/xmlapi/boardgame/132911111111,132911111112?versions=1')->andReturn(simplexml_load_file('tests/132911111111,132911111112.xml'));
    $api = new BoardGameGeekApi($mock);
    $games = $api->getBoardGame(array(132911111111, 132911111112));
    $this->assertEquals(array(), $games);
  }

  public function testSearchBoardGameGeek() {
    $mock = Mockery::mock('PatrickRose\BoardGameGeek\XMLReader');
    $mock->shouldReceive('parse')->with('http://www.boardgamegeek.com/xmlapi/search?search=citadels')->andReturn(simplexml_load_file('tests/search.xml'));
    $api = new BoardGameGeekApi($mock);
    $games = $api->search('citadels');
    $this->assertEquals(array('478' => 'Citadels', '13291' => 'Citadels:  The Dark City'), $games);
  }

  public function testSearchAndGet() {
    $mock = Mockery::mock('PatrickRose\BoardGameGeek\XMLReader');
    $mock->shouldReceive('parse')->with('http://www.boardgamegeek.com/xmlapi/search?search=citadels')->andReturn(simplexml_load_file('tests/search.xml'));
    $mock->shouldReceive('parse')->with('http://www.boardgamegeek.com/xmlapi/boardgame/478,13291?versions=1')->andReturn(simplexml_load_file('tests/478,13291.xml'));
    $api = new BoardGameGeekApi($mock);
    $games = $api->searchAndGet('citadels');
    $this->assertEquals($api->getBoardGame(array(478,13291)), $games);
  }

  public function testGetUsersCollection() {
    $mock = Mockery::mock('PatrickRose\BoardGameGeek\XMLReader');
    $mock->shouldReceive('parse')->with('http://www.boardgamegeek.com/xmlapi/collection/drugcrazed')->andReturn(simplexml_load_file('tests/drugcrazed.xml'));
    $api = new BoardGameGeekApi($mock);
    $games = $api->collection('drugcrazed');
    $this->assertEquals($this->getCollection(), $games);
  }

  public function testGetUsersCollectionAsGames() {
    $mock = Mockery::mock('PatrickRose\BoardGameGeek\XMLReader');
    $mock->shouldReceive('parse')->with('http://www.boardgamegeek.com/xmlapi/collection/drugcrazed')->andReturn(simplexml_load_file('tests/drugcrazed.xml'));
    $mock->shouldReceive('parse')->with('http://www.boardgamegeek.com/xmlapi/boardgame/6249,137408,124742,133500,132005,130806?versions=1')->andReturn(simplexml_load_file('tests/13291,13297.xml'));
    $api = new BoardGameGeekAPI($mock);
    $games = $api->getCollectionAsGames('drugcrazed');
    $this->assertEquals(array($this->getTestGame(), $this->getOtherGame()), $games);
  }

}

?>
