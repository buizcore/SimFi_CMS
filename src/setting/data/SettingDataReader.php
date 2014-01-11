<?php


/**
 * @package com.BuizCore
 * @subpackage SimFi
 */
class SettingDataReader
{

  /**
   * @var array
   */
  public $values = array();

  /**
   * Index der bereits geladenen Value Files
   * @var array
   */
  public $valIdx = array();

  /**
   * @param array $data
   * @param string $key
   */
  public function __construct($key)
  {

    $this->loadValues($key);

  }//end public function __construct */


  /**
   * @param string $key
   * @param string $subKey
   * @return string
   */
  public function getVal($key, $subKey)
  {

    if (!isset($this->values[$key])) {
      if (!$this->loadValues($key)) {
        return 'missing value '.$key;
      }
      if (!isset($this->values[$key])) {
        return 'missing value '.$key;
      }
    }

    // ... well... whatever
    return @!is_null($this->values[$key][$subKey])
    ? $this->values[$key][$subKey]
    : null;

  }//end public function getVal */

  /**
   * @param string $key
   * @return string
   */
  protected function loadValues($key)
  {

    $conf = Conf::getActive();

    $tmp = explode('.', $key);

    if(isset($this->valIdx[$tmp[0]])){
      return true;
    }

    $this->valIdx[$tmp[0]] = true;

    if (file_exists(PATH_ROOT.$conf->page_root.'/content/data/'.$tmp[0].'.php')) {
      include PATH_ROOT.$conf->page_root.'/content/data/'.$tmp[0].'.php';
      return true;
    } else {
      return false;
    }

  }//end protected function loadValues */

}//end class Console */
