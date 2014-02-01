<?php namespace PatrickRose\BoardGameGeek;

class BoardGameGeekAPI {

  const GET_GAME     = 'http://www.boardgamegeek.com/xmlapi/boardgame/';
  const SEARCH_GAMES = 'http://www.boardgamegeek.com/xmlapi/search?search=';
  const USER_COLLECTION = 'http://www.boardgamegeek.com/xmlapi/collection/';

  protected $xmlHander;

  public function __construct(XMLReader $xmlreader) {
    $this->xml = $xmlreader;
  }

  public function getBoardGame($term, $parameters = array()) {

    if(is_array($term)) {

      $url = self::GET_GAME . implode($term, ',') . '?versions=1' . static::buildQueryString($parameters, '&');
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

      $url = self::GET_GAME . $term . '?versions=1' . static::buildQueryString($parameters, '&');
      $xml = $this->xml->parse($url);

      if ($xml->boardgame->error) {
	return null;
      }

      return BoardGame::createFromXML($xml->boardgame);

    }

  }

  public function search($searchTerm, $parameters = array()) {
    $url = self::SEARCH_GAMES . $searchTerm . static::buildQueryString($parameters, '&');
    $xml = $this->xml->parse($url);
    $games = array();

    foreach($xml->boardgame as $game) {
      $games[$game['objectid']->__toString()] = $game->name->__toString();
    }

    return $games;
  }

  public function searchAndGet($searchTerm, $searchParams = array(), $getParams = array()) {
    $games = $this->search($searchTerm, $searchParams);
    return $this->getBoardGame(array_keys($games), $getParams);
  }

  public function collection($username, $parameters = array()) {
    $url = self::USER_COLLECTION . $username . static::buildQueryString($parameters);
    $xml = $this->xml->parse($url);
    $games = array();

    foreach($xml->item as $item) {
      $games[$item['objectid']->__toString()] = $item->name->__toString();
    }

    return $games;

  }

  public function getCollectionAsGames($username, $userParams = array(), $getParams = array()) {
    $games = $this->collection($username, $userParams);
    return $this->getBoardGame(array_keys($games), $getParams);
  }

  public static function buildQueryString($parameters, $start = '?') {
    $string = empty($parameters) ? '' : $start;
    foreach($parameters as $param => $value) {
      $string .= $param . '=' . $value . '&';
    }
    return $string;
  }

}
