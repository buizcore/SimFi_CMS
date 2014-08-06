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
class DbMysqlResult extends Db_Result
{


  /**
   * @return array
   */
  public function getAll()
  {
    $all = array();
    while ($row = $this->result->fetch_assoc()) {
      $all[] = $row;
    }

    return $all;
  }//end public function getAll */

  /**
   * @return array
   */
  public function get()
  {
      
      $this->row = $this->result->fetch_assoc();
      ++$this->pos;
      
      return $this->row;
      
  }//end public function get */
  
  /**
   *
   */
  public function rewind()
  {
      
      $this->result->data_seek( 0 );
       
  }//end public function rewind */

}//end class DbMysqlResult
