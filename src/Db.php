<?php


/**
 * @package com.BuizCore
 * @subpackage SimFi
 */
class Db
{

  const TYPE  = 0;

  const SIZE  = 1;

  const PREC  = 2;

  const REQUIRED = 3;

  const UNIQUE = 4;

  const VALIDATOR = 5;

  const SANITIZER = 6;

  const DEF_VAL = 7;

  const COMMENT = 8;

////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * Liste mit den Datenbankverbindungen
   * @var array
   */
  private static $connections = array();

  /**
   * Liste der DB Admin Objekte
   * @var array
   */
  private static $dbAdmins = array();

////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////


  /**
   * @param string $key
   * @param array $conf
   * @return Db_Connection
   */
  public static function getConnection( $key = 'default', $conf = null)
  {

    if(!isset(self::$connections[$key])){

      if(!$conf)
        $conf = Conf::getActive();

      $dbConf = $conf->db;
      $conConf = $dbConf[$key];

      if (strtolower($conConf['driver'])==='postgresql') {

        self::$connections[$key] = new DbPostgresql($conConf['name'], $conConf['user'], $conConf['pwd'], $conConf['host'], $conConf['port']);

      } else if(strtolower($conConf['driver'])==='mysql') {

        self::$connections[$key] = new DbMysql($conConf['name'], $conConf['user'], $conConf['pwd'], $conConf['host'], $conConf['port']);

      } else {

        throw new DbException('Requested nonsupported db driver: '.$conConf['driver'] );
      }

    }

    return self::$connections[$key];

  }//end public static function getConnection */

}//end class Db
