<?php

/**
 * Klasse für das Management eines Mercurial Repository
 * @package WebFrap
 * @subpackage WebExpert
 *
 * @property string $mail
 * @property string $vorname
 * @property string $nachname
 *
 */
class User
{

  private static $active;

  public $id = null;

  private $data = array();

  /**
   * @param int $id
   * @param array|Db_Connection $db
   */
  public static function init( $id, $db )
  {

    if(is_array($db)){

      $data = $db;

    } else {

      $userData = $db->get('webcms_user',$id,'User');

      if(!$userData)
        return null;

      $data = $userData->getData();
    }

    self::$active = new User($id, $data);

  }//end public static function init */

  /**
   * @return Db_Connection
   */
  public static function getActive()
  {

    if(!self::$active){
      if(isset($_SESSION['user_id'])){
        self::init( $_SESSION['user_id'], Db::getConnection() );
      }
    }

    return self::$active;

  }//end public static function getActive */

  public function __construct($id, $data)
  {

    $this->id = $id;
    $this->data = $data;

  }

  /**
   * Zugriff Auf die Elemente per magic set
   * @param string $key
   * @param mixed $value
   */
  public function set( $key , $value )
  {
    $this->data[$key] = $value;
  }// end of public function __set */

  /**
   * Zugriff Auf die Elemente per magic set
   * @param string $key
   * @param mixed $value
   */
  public function __set( $key , $value )
  {
    $this->data[$key] = $value;
  }// end of public function __set */

  /**
   * Zugriff Auf die Elemente per magic set
   * @param string $key
   * @param mixed $value
   */
  public function __get( $key )
  {
    return isset($this->data[$key]) ? $this->data[$key]:null;
  }// end of public function __get */

  public function htmlSafe($key)
  {
    if (!isset($this->data[$key]) )
      return '';

    return htmlentities($this->data[$key]);
  }

}//end class User */