<?php



/**
 * @package com.BuizCore
 * @subpackage SimFi
 */
class LoginCheck
{

  /**
   * @param string $watchword
   */
  public static function check($watchword)
  {

    $conf = Conf::getActive();

    if ($conf->watch_word == $watchword) {
      $_SESSION['user_knows_pwd'] = true;
    }

  }//end public static function check */

}//end class UserRegister_Controller */
