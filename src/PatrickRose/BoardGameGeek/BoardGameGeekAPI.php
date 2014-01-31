<?php namespace PatrickRose\BoardGameGeek;

class BoardGameGeekAPI {

  const GET_GAME     = 'http://www.boardgamegeek.com/xmlapi/boardgame/';
  const SEARCH_GAMES = 'http://www.boardgamegeek.com/xmlapi/search?search=';
  const USER_COLLECTION = 'http://www.boardgamegeek.com/xmlapi/collection/';

  protected $xmlHander;

  public function __construct(XMLReader $xmlreader) {
    $this->xml = $xmlreader;
  }

  public function getBoardGame($term) {

    if(is_array($term)) {

      $url = self::GET_GAME . implode($term, ',') . '?versions=1';
      $xml = $this->xml->parse($url);
      if ($xml->boardgame->error) {
        return array();
      }

      $games = array();

      foreach($xml->boardgame as $boardGame) {
        $games[] = BoardGame::createFromXML($boardGame);
      }

      return $games;

    }
    else {

      $url = self::GET_GAME . $term . '?versions=1';
      $xml = $this->xml->parse($url);

      if ($xml->boardgame->error) {
	return null;
      }

      return BoardGame::createFromXML($xml->boardgame);

    }

  }

  public function search($searchTerm) {
    $url = self::SEARCH_GAMES . $searchTerm;
    $xml = $this->xml->parse($url);
    $games = array();

    foreach($xml->boardgame as $game) {
      $games[$game['objectid']->__toString()] = $game->name->__toString();
    }

    return $games;
  }

  public function searchAndGet($searchTerm) {
    $games = $this->search($searchTerm);
    return $this->getBoardGame(array_keys($games));
  }

  public function collection($username) {
    $url = self::USER_COLLECTION . $username;
    $xml = $this->xml->parse($url);
    $games = array();

    foreach($xml->item as $item) {
      $games[$item['objectid']->__toString()] = $item->name->__toString();
    }

    return $games;

  }

  public function getCollectionAsGames($username) {
    $games = $this->collection($username);
    return $this->getBoardGame(array_keys($games));
  }

}
