<?php



/**
 * Ausgabe von UI elementen in die shell
 * @package com.BuizCore
 * @subpackage SimFi
 */
abstract class UiConsole
{

  /**
   * @var string
   */
  public $type = 'zenity';

  /**
   * @var array
   */
  private $version = array();

  /**
   * @var Zenity
   */
  private static $active = null;

  /**
   * @return UiConsole
   */
  public static function getActive()
  {

    return self::$active;

  }//end public static function getActive */

  /**
   * @param UiConsole $active
   */
  public static function setActive( $active )
  {

    self::$active = $active;

  }//end public static function setActive */


  /**
   * @param string $text
   */
  public function out( $text )
  {

    echo $text."\n";

  }//end public function out */


  /**
   * @param string $warning
   */
  public static function debugLine( $error )
  {
    self::$active->debug( $error );
  }

}//end class UiConsole

