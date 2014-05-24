<?php namespace PatrickRose/
BoardGameGeek / Facades

use Illuminate\Support\Facades\Facade;

class BoardGameGeekAPI extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'boardgamegeekapi';
    }

}

?>
