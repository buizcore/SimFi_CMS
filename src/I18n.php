<?php
/*******************************************************************************
*
* @author      : Dominik Bonsch <d.bonsch@buizcore.com>
* @date        :
* @copyright   : BuizCore GmbH <contact@buizcore.com>
* @project     : BuizCore, The core business application plattform
* @projectUrl  : http://buizcore.com
*
* @licence     : BSD License see: LICENCE/BSD Licence.txt
*
* @version: @package_version@  Revision: @package_revision@
*
* Changes:
*
*******************************************************************************/

/**
 * @package com.BuizCore
 * @subpackage SimFi
 */
class I18n
{

  /**
   * Sprachdaten
   * @var array
   */
  static $lang = 'de';

  /**
   * Liste mit den sprachdaten
   * @var array
   */
  static $l = array();

  /**
   * Liste mit den sprachdaten
   * @var array
   */
  static $repos = array();

  /**
   * @var array
   */
  static $dec = array(
    'de' => ',',
    'en' => '.'
  );

  /**
   * @var array
   */
  static $mil = array(
    'de' => '.',
    'en' => ','
  );

  /**
   * @var array
   */
  static $dateFormat = array(
    'de' => 'd.m.Y',
    'en' => 'Y-m-d'
  );

  /**
   * @var array
   */
  static $timeFormat = array(
    'de' => 'H:i:s',
    'en' => 'H:i:s'
  );

  /**
   * @param string $lang
   */
  public static function loadLang($key)
  {

    $langKey = substr( $key, 0, strrpos($key,'.') );

    if(isset(self::$repos[$langKey])){
      return;
    }

    self::$repos[$langKey] = true;

    $langPath = str_replace('.','/',$langKey);

    if( file_exists(WEB_ROOT != SIMFI_CODE_PATH &&  WEB_ROOT.'src/i18n/'.self::$lang.'/'.$langPath.'.php')){
      include WEB_ROOT.'src/i18n/'.self::$lang.'/'.$langPath.'.php';
    } else if (file_exists(SIMFI_CODE_PATH.'i18n/'.self::$lang.'/'.$langPath.'.php') ){
      include SIMFI_CODE_PATH.'i18n/'.self::$lang.'/'.$langPath.'.php';
    }

  }//end public static function loadLang */


  /**
   * @param string $key
   * @param string $val
   * @param string $vars
   * @return string
   */
  public static function l($key, $val, $vars = array())
  {

    if (!isset(self::$l[$key.'.'.$val])) {
      self::loadLang($key);
    }

    return isset(self::$l[$key.'.'.$val])?:$val;

  }//end public static function l */

}//end class I18n */