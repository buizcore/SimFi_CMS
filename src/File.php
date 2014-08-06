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
class File
{

  /**
   * @param string $file Link zum File
   * @param int $start Position ab welcher die Daten gelesen werden sollen
   * @param string $delimiter
   * @param string $enclosure
   * @param string $escape
   */
  public static function getCsvContent( $file, $start = 0, $delimiter = ';', $enclosure = '"', $escape    = '\\' )
  {

    $data = array();
    $pos = 0;

    if( ( $handle = fopen( $file, "r")) !== FALSE)
    {

      while (($row = fgetcsv($handle, 4096, $delimiter, $enclosure, $escape )) !== FALSE)
      {
        if( $start > $pos )
        {
          ++$pos;
          continue;
        }

        $data[] = $row;

        ++$pos;
      }

      fclose($handle);
    }

    return $data;

  }//end public static function getCsvContent */


}//end class User */