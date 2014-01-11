<?php


/**
 * @package com.BuizCore
 * @subpackage SimFi
 */
class DbMysqlResult extends Db_Connection
{

  protected $result = null;

  protected $connection = null;


  /**
   *
   */
  public function __construct($result, $connection)
  {
    $this->result = $result;
    $this->connection = $connection;
  }//end public function __construct */


  /**
   *
   */
  public function getAll()
  {
    $all = array();
    while ($row = $this->result->fetch_assoc()) {
      $all[] = $row;
    }

    return $all;
  }//end public function getAll */


}//end class DbMysqlResult
