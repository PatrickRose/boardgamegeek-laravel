<?php namespace PatrickRose\BoardGameGeek;

class BoardGameGeekAPI {

  const GET_GAME     = 'http://www.boardgamegeek.com/xmlapi/boardgame/';
  const SEARCH_GAMES = 'http://www.boardgamegeek.com/xmlapi/search?search=';

  public function getBoardGame($term) {

    if(is_array($term)) {

      $url = self::GET_GAME . implode($term, ',') . '?versions=1';
      $xml = simplexml_load_file($url);

      $games = array();

      foreach($xml->boardgame as $boardGame) {
	$games[] = BoardGame::createFromXML($boardGame);
      }

      return $games;

    }
    else {

      $url = self::GET_GAME . $term . '?versions=1';
      $xml = simplexml_load_file($url);

      if ($xml->boardgame->error) {
	return null;
      }

      return BoardGame::createFromXML($xml->boardgame);

    }

  }

  public function search($searchTerm) {
    $url = self::SEARCH_GAMES . $searchTerm;
    $xml = simplexml_load_file($url);

    $games = array();
    
    foreach($xml->boardgame as $game) {
      $games[] = $game['objectid'];
    }

    return $games;
  }

}
