<?php

namespace PatrickRose\BoardGameGeek;

use Illuminate\Database\Eloquent\ModelNotFoundException;

class BoardGame extends Eloquent {

  $type = "boardgame";

  protected $table = 'bgg_games';

  protected $fillable = ['*'];

  protected $incrementing = false;

  /**
   * Gets a game from the api. If you already have it saved
   * in your database, it'll grab that and update it. Note
   * that this will always query the BGG api, and thus will
   * be much slower than a find(). Use that unless you *have* to have
   * the latest infomation, or want to be sure of getting a game.
   * @param  int  $id The id of the game you're looking for
   * @return BoardGame The game it found
   */
  public static function search($id) {
    try {
      $game = BoardGame::findOrFail($id);
      $game->reload();
    }
    catch (ModelNotFoundException $e) {
      $game = BoardGame::searchBGG($id);
    }
    return $game;
  }

  /**
   * Gets a game from the api. Note that this will query the BGG
   * api, and thus will be fairly slow.
   * @param  int  $id The id of the game you're looking for
   * @return BoardGame The game it found
   */
  public static function searchBGG($id) {
    $response = BoardGame::getXML([$id]);
    return BoardGame::fromXML($response);
  }

  /**
   * Gets the xml representation of some games from the api.
   * Note that this will query the BGG api, and thus will be
   * fairly slow. Use this if you need more information about a game
   * than the defaults.
   * @param  array  $ids An array of ids of games
   * @param  array  $parameters Any parameters you want to pass in.
   * @return SimpleXMLElement The XML representation of those games
   * @throws BGGException If there's a problem getting the games
   */
  public static function getXML($ids, $parameters = ['versions' => '1']) {
    $url = "http://www.boardgamegeek.com/xmlapi/boardgame/" . implode(",", $ids);
    foreach($parameters as $key => $value) {
      $url .= "&" . $key . "=" . $value;
    }
    if (!($xml = simplexml_load_file($url))) {
      throw new BGGException("Failed to load details from the BGG API - maybe BGG is down?");
    }
    if ($xml->count() == 0) {
      throw new BGGException("Not a valid id");
    }
    return $xml;
  }

  /**
   * Get several games from BGG. *Much* faster than doing several
   * queries. Use this wherever possible.
   * @param array $ids The ids of the games you're after
   * @returns array An array of games from BGG
   */
  public static function getManyGames($ids) {
    $xml = BoardGame::getXML($ids);
    $games = [];
    foreach($xml->boardgame as $game) {
      $games[] = BoardGame::fromXML($game);
    }
    return $games;
  }

  /**
   * Gets the games ids that a user owns from BGG.
   * @param string $user The username to look for
   * @returns array An array of game ids
   */
  public static function getUsersCollection($user) {
    $url = "http://www.boardgamegeek.com/xmlapi/collection/" . $user . "?own=1";
    if (!($xml = simplexml_load_file($url))) {
      throw new BGGException("Failed to load details from the BGG API - maybe BGG is down?");
    }
    $ids = []
    foreach($xml->item as $item)
    {
      $ids[] = $item['objectid']);
    }
    return $ids;
  }

  /**
   * Creates a new BoardGame from an XML representation
   * @param SimpleXMLElement $xml The XML representation of a game
   * @returns BoardGame A new BoardGame
   */
  public static function fromXML($xml) {
    $details = ['id'           => $xml['objectid'],
                'minplayers'   => $xml->minplayers,
                'maxplayers'   => $xml->maxplayers,
                'playingtime'  => $xml->playingtime,
                'age'          => $xml->age,
                'name'         => BoardGame::getNameFromXML($xml),
                'description'  => $xml->description];
    $game = new BoardGame($details);
  }

  /**
   * Gets the name of the game from the XML
   * @param SimpleXMLElement $xml The XML representation of a game
   * @returns string The name of the game
   */
  public static function getNameFromXML($xml) {
    foreach($xml->name as $name)
    {
      if (isset($name['primary']))
      {
        return $name->toString();
      }
    }
    return BGGException("Couldn't find the name of the game");
  }

  /**
   * Reload the information from BGG.
   */
  public function reload() {
    try {
      $game = BoardGame::searchBGG($this->id);
      $this = $this->replicate($game);
    }
    catch (BGGException $e) {
    }
  }

}
