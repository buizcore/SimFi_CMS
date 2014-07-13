<?php


/**
 * @package com.BuizCore
 * @subpackage SimFi
 */
class Request
{

  /**
   * @var array
   */
  static $args = array();

  /**
   * @var IsARequest
   */
  private static $active = null;

  /**
   * @return IsARequest
   */
  public static function getActive()
  {
    return self::$active;
  }//end public static function getActive */

  /**
   * @param IsARequest $request
   */
  public static function setActive( IsARequest $request )
  {
    self::$active = $request;
  }//end public static function setActive */

////////////////////////////////////////////////////////////////////////////////
// Static Request Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param array $args
   */
  public static function parseRequest( $args = null )
  {

    if( IS_CLI ) {
      if( 1 < count($args)) {
        $parsed = '';
        parse_str($args[1],$parsed);

        self::$args = $parsed;
      }

      self::$active = new RequestCli( $args );
      return self::$active;
    
    } else {
      
      self::$active = new RequestHttp(  );
      return self::$active;
    }

  }//end public static function parseRequest */

  /**
   * @param string $key
   * @param string $default Der default Wert welcher zurückgegeben wird
   *   wenn für den key keine
   */
  public static function arg( $key, $default = null )
  {

    if( IS_CLI ) {
      return isset( self::$args[$key] ) ? self::$args[$key] : $default;
    } else {
      return isset( $_GET[$key] ) ? $_GET[$key] : $default;
    }

  }//end public static function arg */

  /**
   * @param string $key
   * @param string $default Der default Wert welcher zurückgegeben wird
   *   wenn für den key keine
   */
  public static function data( $key, $default = null )
  {

    return isset( $_POST[$key] ) ? $_POST[$key] : $default;

  }//end public static function data */

}//end class Request */
