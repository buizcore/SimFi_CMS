<?php


/**
 * @package com.BuizCore
 * @subpackage SimFi
 *
 * @property int rowid
 * @property string title
 * @property string page_key
 * @property string meta_description
 * @property string template_content
 * @property int version
 * @property date created
 * @property date last_update
 */
class Page_Entity extends DbEntity
{

  /**
   * @var string name der tabelle
   */
  public static $table = 'simfi_page';

  /**
   * @var string metadaten für die datenbank felder
   */
  public static $cols = array(
    'rowid' => array(
      Db::TYPE => 'int',
      Db::REQUIRED => false,
      Db::VALIDATOR => Validator::INT
    ),
    'title' => array(
      Db::TYPE => 'text',
      Db::SIZE => 250,
      Db::REQUIRED => true,
      Db::VALIDATOR => Validator::TEXT
    ),
    'page_key' => array(
      Db::TYPE => 'text',
      Db::SIZE => 250,
      Db::REQUIRED => true,
      Db::VALIDATOR => Validator::TEXT
    ),
    'meta_description' => array(
      Db::TYPE => 'text',
      Db::VALIDATOR => Validator::TEXT
    ),
    'template_content' => array(
      Db::TYPE => 'text',
      Db::VALIDATOR => Validator::TEXT
    ),
    'version' => array(
      Db::TYPE => 'int',
      Db::VALIDATOR => Validator::INT
    ),
    'created' => array(
      Db::TYPE => 'date',
      Db::VALIDATOR => Validator::DATE
    ),
    'last_update' => array(
      Db::TYPE => 'date',
      Db::VALIDATOR => Validator::DATE
    ),
    

  );

}//end class User_Entity */
