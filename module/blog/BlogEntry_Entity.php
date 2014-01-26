<?php


/**
 * 
 * @package com.BuizCore
 * @subpackage SimFi
 *
 * @property int rowid
 * @property string titel
 * @property string content
 * @property string template
 * @property string images
 * @property string images_big
 * @property string tags
 * @property string author
 * @property string created
 * @property string last_update
 */
class BlogEntry_Entity extends DbEntity
{

  /**
   * @var string name der tabelle
   */
  public static $table = 'simfi_blog_entry';

  /**
   * @var string metadaten für die datenbank felder
   */
  public static $cols = array(
    'rowid' => array(
      Db::TYPE => 'int',
      Db::REQUIRED => false,
      Db::VALIDATOR => Validator::INT
    ),
    'titel' => array(
      Db::TYPE => 'text',
      Db::SIZE => 250,
      Db::REQUIRED => true,
      Db::VALIDATOR => Validator::TEXT
    ),
    'content' => array(
      Db::TYPE => 'text',
      Db::REQUIRED => true,
      Db::VALIDATOR => Validator::TEXT
    ),
    'template' => array(
      Db::TYPE => 'text',
      Db::SIZE => 250,
      Db::REQUIRED => true,
      Db::VALIDATOR => Validator::TEXT
    ),
    'images' => array(
      Db::TYPE => 'text',
      Db::VALIDATOR => Validator::TEXT
    ),
    'images_big' => array(
      Db::TYPE => 'text',
      Db::VALIDATOR => Validator::TEXT
    ),
    'tags' => array(
      Db::TYPE => 'text',
      Db::VALIDATOR => Validator::TEXT
    ),
    'author' => array(
      Db::TYPE => 'text',
      Db::SIZE => 250,
      Db::VALIDATOR => Validator::TEXT
    ),
    'created' => array(
      Db::TYPE => 'date',
      Db::VALIDATOR => Validator::DATE
    ),
    'last_update' => array(
      Db::TYPE => 'date',
      Db::VALIDATOR => Validator::DATE
    ),
    'active' => array(
      Db::TYPE => 'smallint',
      Db::VALIDATOR => Validator::INT
    ),

  );

}//end class User_Entity */
