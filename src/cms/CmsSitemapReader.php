<?php


/**
 * @package com.BuizCore
 * @subpackage SimFi
 */
class CmsSitemapReader
{
    
    public $langs = array();
    
    public $pages = array();
    
    public $files = array();
  
    public function __construct()
    {
        include CONF_PATH.'conf/sitemap.php';
    }
    

}//end class CmsSitemapReader */
