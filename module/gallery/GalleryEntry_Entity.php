<?php


/**
 * 
 * @subpackage web_expert.cms
 *
 * @property int rowid
 * @property string g_key
 * @property string img_name
 * @property string img_name_g
 * @property string title
 * @property string content
 * @property string tags
 * @property string author
 * @property string created
 * @property string last_update
 */
class GalleryEntry_Entity extends DbEntity
{

  /**
   * @var string name der tabelle
   */
  public static $table = 'bc_gallery_entry';

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
      Db::TYPE => 'g_key',
      Db::SIZE => 250,
      Db::REQUIRED => true,
      Db::VALIDATOR => Validator::TEXT
    ),
    'img_name' => array(
      Db::TYPE => 'text',
      Db::REQUIRED => true,
      Db::VALIDATOR => Validator::TEXT
    ),
    'img_name_big' => array(
      Db::TYPE => 'text',
      Db::REQUIRED => true,
      Db::VALIDATOR => Validator::TEXT
    ),
    'title' => array(
      Db::TYPE => 'text',
      Db::VALIDATOR => Validator::TEXT
    ),
    'content' => array(
      Db::TYPE => 'text',
      Db::VALIDATOR => Validator::TEXT
    ),
    'tags' => array(
      Db::TYPE => 'text',
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
    'author' => array(
      Db::TYPE => 'text',
      Db::SIZE => 250,
      Db::VALIDATOR => Validator::TEXT
    ),
    'active' => array(
      Db::TYPE => 'smallint',
      Db::VALIDATOR => Validator::INT
    ),

  );

}//end class User_Entity */
