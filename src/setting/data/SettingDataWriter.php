<?php


/**
 * 
 * @subpackage web_expert
 */
class SettingDataWriter
{

  public $data = array();

  public $key = null;

  /**
   * @param array $data
   * @param string $key
   */
  public function __construct($data, $key)
  {

    $this->data = $data;
    $this->key = $key;

  }//end public function __construct */



  /**
   * @param $filePath
   */
  public function write( $filePath )
  {

    $file = <<<FILE
<?php

FILE;

    foreach ($this->data as $key => $value) {

      $file .= <<<FILE
\$this->values['{$this->key}']['{$key}'] = <<<TEXT
{$value}
TEXT;

FILE;
    }

    if (!is_writable($filePath)) {
      return $filePath.' Keine Berechtigung in die Datei zu schreiben';
    }

    if (!file_put_contents($filePath, $file)) {
      return $filePath.' Es ist ein Fehler beim Schreiben aufgetreten';
    }

    return 'ok';

  }//end public function write */


}//end class Console */
