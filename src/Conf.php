<?php


/**
 * @package com.BuizCore
 * @subpackage SimFi
 *
 * @property string $page_root pfad zum webseite content
 * @property string $fw_root wo befindet sich der Framework code
 * @property string $title der html title
 * @property string $start_page
 * @property string $theme
 * @property string $img_path pfad im browser zu den bilder
 * @property string $files_path wo werden files abgelegt
 *
 * @property string $pwd_salt
 * @property array $db // informationen zur datenbank
 * @property string $admin_mail
 * @property string $def_mail_sender
 * @property array $admin_users
 *
 * @property string $custom_user
 * @property string $watchword
 *
 * @property string project_label
 *
 */
class Conf
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * Die aktive Ko
   * @var Conf
   */
  private static $active = null;

  /**
   * @var array
   */
  protected $settings = array();

  /**
   * Routen auf andere Controller
   * @var array
   */
  public $routes = array();

  /**
   * Routen für die Seiten
   * @var array
   */
  public $pageRoutes = array();

////////////////////////////////////////////////////////////////////////////////
// Static methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * Laden der Konfiguration
   */
  public static function init()
  {

    self::$active = new Conf();
    SimFi::$conf = self::$active;

  }// end public static function init */

  /**
   * @return Conf
   */
  public static function getActive()
  {

    return self::$active;

  }// end public static function getActive */

////////////////////////////////////////////////////////////////////////////////
// Constructor
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $domain
   */
  public function __construct( $domain = null )
  {

    if (is_null($domain))
      $domain = $_SERVER['SERVER_NAME'];

    if( file_exists( CONF_PATH.'conf/host/'.$domain.'/conf.php' ) )
      include CONF_PATH.'conf/host/'.$domain.'/conf.php';
    else
      include CONF_PATH.'conf/host/web/conf.php';
    
    // routen laden
    if(file_exists(CONF_PATH.'conf/routes.php'))
        include CONF_PATH.'conf/routes.php';
    
    if(!isset($this->settings['base_url'])){
        $this->settings['base_url'] = 'http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['SCRIPT_NAME']).'/';
    }
    
    if(!isset($this->settings['ssl_base_url'])){
        $this->settings['ssl_base_url'] = 'https://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['SCRIPT_NAME']).'/';
    }

  }//end public function __construct */

  /**
   * Zugriff Auf die Elemente per magic set
   * @param string $key
   * @param mixed $value
   */
  public function __set( $key , $value )
  {
    $this->settings[$key] = $value;
  }// end of public function __set */

  /**
   * Zugriff Auf die Elemente per magic get
   *
   * @param string $key
   * @return mixed
   */
  public function __get( $key )
  {
    return isset($this->settings[$key])?$this->settings[$key]:null;
  }// end of public function __get */

  /**
   * Zugriff Auf die Elemente per magic get
   *
   * @param string $key
   * @return mixed
   */
  public function __isset( $key )
  {
    return isset($this->settings[$key])?true:false;
  }// end of public function __isset */


} // end class Conf


