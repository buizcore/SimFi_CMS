<?php



/**
 * @package com.BuizCore
 * @subpackage SimFi
 */
class User_Entity extends DbEntity
{

  public static $cols = array(
    'name' => array(
      Db::LABEL => 'text',
      Db::SIZE => 250,
      Db::REQUIRED => true,
      Db::UNIQUE = true,
      Db::VALIDATOR = Validator::EMAIL
    ),
    'password' => array(
      Db::LABEL => 'text',
      Db::SIZE => 250,
      Db::REQUIRED = true,
      Db::VALIDATOR = Validator::TEXT
    ),
    'firstname' => array(
      Db::LABEL => 'text',
      Db::SIZE => 250,
      Db::REQUIRED = true,
      Db::VALIDATOR = Validator::TEXT
    ),
    'lastname' => array(
      Db::LABEL => 'text',
      Db::SIZE => 250,
      Db::REQUIRED = true,
      Db::VALIDATOR = Validator::TEXT
    )

  );

}//end class User_Entity */
