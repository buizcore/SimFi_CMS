<?php

class AuthFreeRadius
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
     * Erstellt einen Benutzer
     * @param string $username
     * @param string $password
     * @param int $sessionTimeout
     */
    public function createUser($username, $password, $sessionTimeout = 120)
    {
        
        try {
            $this->db->begin();
            $sql = sprintf("INSERT INTO radcheck values ('','%s','Password',':=','%s')", $this->db->escape($username), $this->db->escape($password));
            $this->db->insert($sql);
            $sql = sprintf("INSERT INTO radcheck values ('','%s','Max-All-Session',':=','%s')", $this->db->escape($username), $this->db->escape($sessionTimeout));
            $this->db->insert($sql);
            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollback();
        }
        
    }//end public function createUser */

    /**
     * Löscht den Benutzer
     *
     * @param string $username
     */
    public function deleteUser($username)
    {
        
        $sql = sprintf("DELETE FROM radcheck WHERE username=%s", $this->db->escape($username));
        $this->db->delete($sql);
        
    }//end public function deleteUser */
    
}//end AuthFreeRadius */
