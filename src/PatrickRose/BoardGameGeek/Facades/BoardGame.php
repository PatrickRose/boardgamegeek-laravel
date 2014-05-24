<?php namespace PatrickRose/
BoardGameGeek / Facades

use Illuminate\Support\Facades\Facade;

class BoardGame extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'boardgame';
    }

}

?>
