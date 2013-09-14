<?php

namespace Patrickrose\Boardgamegeek;

class Thing {

    protected $type;

    public function getThing($ids, $parameters) {
        $url = "http://www.boardgamegeek.com/xmlapi/" . $type . '/' . implode(",", $ids);
        foreach($parameters as $key => $value) {
            $url .= "&" . $key . "=" . $value;
        }
        if (!($xml = simplexml_load_file($url))) {
            throw new BGGException("Failed to load details from the BGG API");
        }
        if ($xml->count() == 0) {
            throw new BGGException("Not a valid id");
        }
        return $xml;
    }

}