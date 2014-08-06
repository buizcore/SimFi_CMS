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
