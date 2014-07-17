<?php

class FreeRadius
{
    /**
     * @var DbMysql
     */
    private $db;


    /**
     * @param DbMysql $dbConnection
     */
    function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Erstellt einen Benutzer
     * @param string $username
     * @param string $password
     */
    function createUser($username, $password, $sessionTimeout = 120)
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
    }

    /**
     * Löscht den Benutzer
     *
     * @param string $username
     */
    function deleteUser($username)
    {
        $sql = sprintf("DELETE FROM radcheck WHERE username=%s", $this->db->escape($username));
        $this->db->delete($sql);
    }
}
