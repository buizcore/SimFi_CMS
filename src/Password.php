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
class Password
{

    /**
     * wrapper method for password encryption
     *
     * @param string $value            
     * @return string sha1 hash
     */
    public static function passwordHash($value, $mainSalt = '', $dynSalt = '')
    {
        return sha1($mainSalt . $dynSalt . $value);
    } // end public static function passwordHash */
    
    /**
     *
     * @return string
     */
    public static function createSalt($size = 10)
    {
        return substr(uniqid(mt_rand(), true), 0, $size);
    } // end public static function createSalt */
    
    /**
     *
     * @return string
     */
    public static function uniqueToken($size = 12)
    {
        return substr(uniqid(mt_rand(), true), 0, $size);
    } // end public static function uniqueToken */
    
}// end Password


