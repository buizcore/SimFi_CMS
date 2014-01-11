<?php



/**
 * @package com.BuizCore
 * @subpackage SimFi
 */
class UserManagement
{
  
  /**
   * @var Db_Connection
   */
  public $db = null;
  
  /**
   * @var ProtocolWriter
   */
  public $protocol = null;
  
  /**
   * @param Db_Connection $db
   * @param ProtocolWriter $protocol
   */
  public function __construct( $db, $protocol )
  {
    $this->db       = $db;
    $this->protocol = $protocol;
  }//end public function __construct */

  /**
   * Einen neuen User erstellen
   * @param UserDataInf $user
   */
  public function createUser( UserDataInf $user )
  {
    
    $userName = $user->getName();
    
    if( $this->userExists( $userName ) )
    {
      $this->protocol->info( "Systemuser {$userName} allready exists." );      
      return;
    }
    
    $sqlPerson = <<<SQL
INSERT INTO core_person
( firstname, lastname  )
VALUES
( '{$user->getFirstname()}', '{$user->getLastname()}' );
SQL;

    $idPerson = $this->db->insert( $sqlPerson );
    $passwd = Password::passwordHash( $user->getPasswd() );
    
    $sqlUser = <<<SQL
INSERT INTO wbfsys_role_user
( 
  name, 
  id_person, 
  inactive, 
  non_cert_login,
  profile,
  level,
  password
)
VALUES
( 
  '{$userName}', 
  {$idPerson}, 
  FALSE, 
  TRUE, 
  '{$user->getProfile()}', 
  '{$user->getLevel()}',
  '{$passwd}'
);
SQL;

    $idUser = $this->db->insert( $sqlUser );
    
  }//end public function createUser */
  
  /**
   * Einen vorhandenen user updaten
   * @todo bessere fehlerbehandlung
   * @param UserDataInf $user
   */
  public function updateUser( UserDataInf $user )
  {
    
    $userName = $user->getName();
    
    $userId = $this->getUserId( $userName );
    $passwd = Password::passwordHash( $user->getPasswd() );
    
    $sqlUser = <<<SQL
UPDATE wbfsys_role_user
SET
  name = '{$userName}', 
  inactive = FALSE, 
  non_cert_login = TRUE,
  profile = '{$user->getProfile()}',
  level = '{$user->getLevel()}',
  password = '{$passwd}'
WHERE rowid = {$userId}
;
SQL;
  
    $this->db->update( $sqlUser );
    
    $personId = $this->db->select( 'SELECT id_person from wbfsys_role_user where rowid = '.$userId );
    
    
    $sqlPerson = <<<SQL
UPDATE core_person
SET
firstname = '{$user->getFirstname()}', 
lastname = '{$user->getLastname()}'
WHERE rowid = {$personId};
SQL;

    $this->db->update( $sqlPerson );
    
  }//end public function updateUser */
  

  /**
   * Prüfen ob ein bestimmter Benutzer bereits im System existiert
   * @param string $user
   */
  public function userExists( $user )
  {
    
    if( is_object( $user ) )
      $user = $user->getName();
        
    return (boolean)$this->db->select( 'SELECT COUNT(rowid) as pos from wbfsys_role_user where upper(name) = upper(\''.$user.'\') ', 'pos' );

  }//end public function createUser */
  
  /**
   * Prüfen ob ein bestimmter Benutzer bereits im System existiert
   * @param string $user
   */
  public function getUserId( $user )
  {
    
    return $this->db->select( 'SELECT rowid as user_id from wbfsys_role_user where upper(name) = upper(\''.$user.'\') ', 'user_id' );

  }//end public function getUserId */
  
  /**
   * @param string $user
   */
  public function dropUser( $user )
  {
    
    $person = $this->db->select( 'SELECT id_person from wbfsys_role_user where upper(name) = upper(\''.$user.'\') ', 'id_person', true );
    
    $this->db->delete( 'DELETE FROM wbfsys_role_user WHERE upper(name) = upper(\''.$user.'\') ' );
    
    if( $person )
      $this->db->delete( 'DELETE FROM core_person WHERE rowid = '.$person );
    
  }//end public function dropUser */
  
}//end class UserManagement */
