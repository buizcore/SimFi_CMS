<?php

/**
 * @package WebFrap
 * @subpackage WebFrap
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


