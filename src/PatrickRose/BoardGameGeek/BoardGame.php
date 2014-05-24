<?php namespace PatrickRose\BoardGameGeek;

class BoardGame
{

    public function __set($property, $value)
    {
        if ($property == 'description') {
            $value = str_replace('<br/>', '\n', $value);
        }
        $this->$property = $value;
    }

    public function __construct($attributes = array())
    {
        foreach ($attributes as $attribute => $value) {
            $this->$attribute = $value;
        }
    }

    public static function create($attributes = array())
    {
        return new static($attributes);
    }

    public static function createFromXML($boardGame)
    {
        $attributes = array();

        $attributes['id'] = $boardGame['objectid']->__toString();
        $attributes['yearpublished'] = $boardGame->yearpublished->__toString();
        $attributes['minplayers'] = $boardGame->minplayers->__toString();
        $attributes['maxplayers'] = $boardGame->maxplayers->__toString();
        $attributes['playingtime'] = $boardGame->playingtime->__toString();
        // Sort out the name
        foreach ($boardGame->name as $name) {
            if (isset($name['primary'])) {
                $attributes['name'] = $name->__toString();
            }
        }
        $attributes['description'] = $boardGame->description->__toString();
        $attributes['image'] = $boardGame->image->__toString();
        $attributes['thumbnail'] = $boardGame->thumbnail->__toString();

        return new BoardGame($attributes);
    }

}

?>
