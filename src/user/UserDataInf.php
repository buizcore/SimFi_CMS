<?php



/**
 * @package WebFrap
 * @subpackage WebExpert
 */
interface UserDataInf
{
  
////////////////////////////////////////////////////////////////////////////////
// 
////////////////////////////////////////////////////////////////////////////////

  
  /**
   * @return string
   */
  public function getName();
  
  /**
   * @return string
   */
  public function getPasswd();

  /**
   * @return string
   */
  public function getFirstname();
  
  /**
   * @return string
   */
  public function getLastname();

  
  /**
   * @return string
   */
  public function getLevel();
  
  /**
   * @return string
   */
  public function getProfile();
  
  /**
   * @return string
   */
  public function getEmail();
  
}//end class UserDataInf */
