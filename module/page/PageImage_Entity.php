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
class PageImage_Entity extends DbEntity
{

  /**
   * @var string name der tabelle
   */
  public static $table = 'simfi_page_image';

  /**
   * @var string metadaten für die datenbank felder
   */
  public static $cols = array(
    'rowid' => array(
      Db::TYPE => 'int',
      Db::REQUIRED => false,
      Db::VALIDATOR => Validator::INT
    ),
    'id_page' => array(
      Db::TYPE => 'int',
      Db::REQUIRED => true,
      Db::VALIDATOR => Validator::INT
    ),
    'id_image' => array(
      Db::TYPE => 'int',
      Db::REQUIRED => true,
      Db::VALIDATOR => Validator::INT
    ),

  );

}//end class User_Entity */
