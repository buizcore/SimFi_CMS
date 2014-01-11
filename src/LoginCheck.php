<?php



/**
 * @package WebFrap
 * @subpackage WebExpert
 */
class LoginCheck
{

  /**
   * @param string $watchword
   */
  public static function check($watchword)
  {

    $conf = Conf::getActive();

    if ($conf->watchword == $watchword) {
      $_SESSION['user_knows_pwd'] = true;
    }

  }//end public static function check */

}//end class UserRegister_Controller */
