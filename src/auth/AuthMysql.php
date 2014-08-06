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

class AuthMysql
{
    
    /**
     * @var DbMysql
     */
    private $db;


    /**
     * @param DbMysql $dbConnection
     */
    public function __construct($db)
    {
        $this->db = $db;
    }//end public function __construct */

    
    /**
     * @param string $userName
     * @param string $plainPwd
     * 
     * @return boolean
     */
    public function checkLogin($userName, $plainPwd)
    {

        $saveUser = $this->db->escape($userName);
        
        $sql = <<<SQL
SELECT
    user_name,
    password 
FROM sys_user
    WHERE user_name = {$saveUser};
      
SQL;
        
        
        $data = $this->db->select($sql)->get();
        
        if (!$data) {
            return false;
        }
        
        $uHash = new UtilHash();
        
        return $uHash->passwordVerify($plainPwd, $data['password']);
        
        
    }//end public function checkLogin */

    
}//end AuthMysql */
