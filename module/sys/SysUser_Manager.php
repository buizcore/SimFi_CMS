<?php


/**
 * @package com.BuizCore
 * @subpackage Wacport
 *
 * @property int rowid
 * @property string id_customer
 * @property string device_mac
 */
class SysUser_Manager
{
    
    /**
     * @var BuizcoreConnector_Client
     */
    public $syncConnector = null;
    
    /**
     * 
     */
    public function syncUsers()
    {
        
        $db = Db::getConnection('default');
        
        $conf = Conf::getActive();
        $buizConf = $conf->buizcore;
        $syncConnector = new BuizcoreConnector_Client($buizConf['default']);
        
        
        // syncen der userdaten
        $users = $syncConnector->get('json.php?c=Buiz.UserSync.listGoupUsers&groups=web_admin');
        if ( $users && !is_string($users) && $users->body ) {
            
            foreach ($users->body as $user) {
        
                $userData = (array)$user;
                
                /* @var  $userNode SysUser_Entity  */
                $userNode  = $db->get('sys_user',$userData['user_id']);
                $userNode->user_name = $userData['user_name'];
                $userNode->firstname = $userData['firstname'];
                $userNode->lastname = $userData['lastname'];
                $userNode->id_person = $userData['person_id'];
                $userNode->language = 'de';
                $userNode->email = $userData['email'];
                $userNode->password = $userData['password'];
                $userNode->created = date('Y-m-d H:i:s');

                if ($userNode->rowid) {
                    
                    $db->update($userNode);
                    
                } else {
                    $userNode->rowid = $userData['user_id'];
                    $db->insert($userNode);
                }
        
            }
        
        }  else {
            
            echo 'Failed to import the users'.NL;
            
            if(is_string($users))
                echo $users;
            else {
                var_dump($users);
            }
        }
        
    }//end public function syncUsers */
    

}//end class SysUser_Manager */
