<?php


/**
 * @package com.BuizCore
 * @subpackage Wacport
 *
 * @property int rowid
 * @property string user_name
 * @property string firstname
 * @property string lastname
 * @property string id_company
 * @property int id_person
 * @property string company_name
 * @property string email
 * @property string mobile
 * @property string created
 * @property string password
 */
class SysUser_Entity extends DbEntity
{

  /**
   * @var string name der tabelle
   */
  public static $table = 'sys_user';
  
  public static $pkSequence = false;

  /**
   * @var string metadaten für die datenbank felder
   */
  public static $cols = array(
    'rowid' => array(
      Db::TYPE => 'int',
      Db::REQUIRED => false,
      Db::VALIDATOR => Validator::INT
    ),
    'user_name' => array(
      Db::TYPE => 'text',
      Db::SIZE => 250,
      Db::REQUIRED => true,
      Db::VALIDATOR => Validator::TEXT
    ),
    'firstname' => array(
      Db::TYPE => 'text',
      Db::SIZE => 250,
      Db::REQUIRED => false,
      Db::VALIDATOR => Validator::TEXT
    ),
    'lastname' => array(
      Db::TYPE => 'text',
      Db::SIZE => 250,
      Db::REQUIRED => true,
      Db::VALIDATOR => Validator::TEXT
    ),
    'id_person' => array(
      Db::TYPE => 'int',
      Db::REQUIRED => false,
      Db::VALIDATOR => Validator::INT
    ),
    'id_company' => array(
      Db::TYPE => 'int',
      Db::REQUIRED => false,
      Db::VALIDATOR => Validator::INT
    ),
    'company_name' => array(
      Db::TYPE => 'text',
      Db::SIZE => 250,
      Db::REQUIRED => false,
      Db::VALIDATOR => Validator::TEXT
    ),
    'email' => array(
      Db::TYPE => 'text',
      Db::SIZE => 250,
      Db::REQUIRED => false,
      Db::VALIDATOR => Validator::TEXT
    ),
    'mobile' => array(
      Db::TYPE => 'text',
      Db::SIZE => 250,
      Db::REQUIRED => false,
      Db::VALIDATOR => Validator::TEXT
    ),
    'password' => array(
      Db::TYPE => 'text',
      Db::SIZE => 250,
      Db::REQUIRED => true,
      Db::VALIDATOR => Validator::TEXT
    ),
    'created' => array(
      Db::TYPE => 'date',
      Db::VALIDATOR => Validator::DATE
    ),

  );

}//end class User_Entity */
