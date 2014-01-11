<?php


/**
 * @package com.BuizCore
 * @subpackage SimFi
 */
class DbEntity
{

  protected $data = array();

  public function __construct( $data = array() )
  {
    $this->setData($data);
  }

  public function getTable()
  {
    return static::$table;
  }

  public function getValidator($key)
  {
    return static::$cols[$key][Db::VALIDATOR];
  }

  public function getCols()
  {
    return array_keys(static::$cols);
  }

  public function __get($key)
  {
    return isset($this->data[$key])?$this->data[$key]:null;
  }

  public function __set($key,$val)
  {
    $this->data[$key] = $val;
  }

  public function getData()
  {
    return $this->data;
  }

  public function setData( $data )
  {
    $this->data = $data;

    if(isset($this->data['rowid'])) {
      //$this->rowid = $this->data['rowid'];
      //unset($this->data['rowid']);
    }
  }

  public function escaped($key, $db)
  {

    if (!isset($this->data[$key]) )
      return 'NULL';

    return $db->escape($this->data[$key], static::$cols[$key][Db::TYPE]);
  }

  public function htmlSafe($key)
  {
    if (!isset($this->data[$key]) )
      return '';

    return htmlentities($this->data[$key],null, 'UTF-8' );
  }

  public function htmlCheckbox($key)
  {
    if (!isset($this->data[$key]) )
      return '';

    return ' ckecked="checked" ';
  }

  public function isEmpty($key)
  {
    if (!isset($this->data[$key]) )
      return true;

    return trim($this->data[$key])==''?true:false;
  }

}