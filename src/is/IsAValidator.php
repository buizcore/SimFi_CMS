<?php


  
/**
 * Interface für die Validatoren
 * @package com.BuizCore
 * @subpackage SimFi
 */
interface IsaValidator
{

  /**
   * @param string $value
   * @param Db_Connection $db
   * @param int $flags
   */
  public function validate( $value, $db = null, $flags = null );
  
  public function santisize( $value, $db = null, $flags = null );
  
  public function validateToContainer( $value, $key, $container, $db = null, $flags = null );
  
  public function santisizeToContainer( $value, $key, $container, $db = null, $flags = null );
  
}//end class IsaValidator */

