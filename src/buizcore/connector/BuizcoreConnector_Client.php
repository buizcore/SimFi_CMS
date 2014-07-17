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
 * @package com.buizcore
 * @subpackage buizcore
 * @author Dominik Bonsch <BuizCore.com>
 * @copyright BuizCore.com <BuizCore.com>
 * @licence BuizCore.com
 */
class BuizcoreConnector_Client
{

    public $gatewayUrl = null;
    
    /** 
     * ssl per default
     * @var int
     */
    public $serverIP = null;
    
    /**
     * ssl per default
     * @var boolean 
     */
    public $ssl = true;
    
    /**
     * username
     * @var string 
     */
    public $userName = null;
    
    /**
     * das passwort
     * @var string 
     */
    public $password = null;
    
    /**
     * @var array
     */
    public $respHeader = array();
    

    /**
     * @param array $conf
     */
    public function __construct($conf = array())
    {
    
        if ($conf) {
            $this->gatewayUrl = $conf['url'];
            $this->userName = $conf['user'];
            $this->password = $conf['pwd'];
            $this->ssl = $conf['ssl'];
        }
        
    }//end public function __construct */
    
    /**
     * @param string $method
     * @param string $subUrl
     * @param string $postData
     * 
     * @return json
     */
    public function request( $method, $subUrl, $postData = array() )
    {
        $handler = curl_init();
        
        $url = 'http';
        if ($this->ssl) {
            $url .= 's';
        }
        
        $url .= '://'.$this->gatewayUrl.'/'.$subUrl;
        
        curl_setopt( $handler, CURLOPT_URL, $url );
        curl_setopt( $handler, CURLOPT_RETURNTRANSFER, true);
        curl_setopt( $handler, CURLOPT_HEADER, false);
        
        $userAgent = 'BuizCore Rest Client 1.0';
        $header = array(
            "Accept-Charset: utf-8;q=0.7",
            "Keep-Alive: 300"
        );
        
        curl_setopt( $handler, CURLOPT_USERAGENT, $userAgent );
        curl_setopt( $handler, CURLOPT_HTTPHEADER, $header );
        
        if ($this->ssl) { 
            curl_setopt( $handler, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt( $handler, CURLOPT_SSL_VERIFYHOST, false);
        }
        
        if ('post' == $method) {

            curl_setopt($handler, CURLOPT_POST, true);
            curl_setopt($handler, CURLOPT_POSTFIELDS, $postData);
            
        } else if ('put' == $method) {

            curl_setopt( $handler, CURLOPT_PUT, true);
            curl_setopt( $handler, CURLOPT_POSTFIELDS, $postData);
            
        } else if ('delete' == $method) {

            curl_setopt( $handler, CURLOPT_CUSTOMREQUEST, "DELETE");
        }
        
        if ($this->userName) {
            curl_setopt( $handler, CURLOPT_USERPWD, $this->userName.':'.$this->password);
        }
        
        $result = curl_exec($handler);
        
        //$this->respHeader = 
        
        curl_close($handler);
        
        $jsonData = json_decode($result);
        
        if ($jsonData) {

            return $jsonData;
            
        } else {

            return $result;
        }
        
    }//end public function request */
    
    /**
     * @param string $subUrl
     */
    public function get( $subUrl )
    {
        
        return $this->request('get', $subUrl);
        
    }//end public function get */
    
    /**
     * @param string $subUrl
     * @param array $postData
     */
    public function post( $subUrl, $postData = array() )
    {
        return $this->request('post', $subUrl, $postData);
    }
    
    /**
     * @param string $subUrl
     * @param array $postData
     */
    public function put( $subUrl, $postData = array() )
    {
        return $this->request('put', $subUrl, $postData);
    }//end public function put */
    
    /**
     * @param string $subUrl
     */
    public function delete( $subUrl )
    {
        
        return $this->request('delete', $subUrl);
        
    }//end public function delete */


}//end BuizcoreConnector_Client

