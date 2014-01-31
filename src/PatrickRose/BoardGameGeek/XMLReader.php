<?php namespace PatrickRose\BoardGameGeek;

class XMLReader {

  public function parse($url) {
    var_dump($url);
    return \simplexml_load_file($url);
  }
  
}

?>
