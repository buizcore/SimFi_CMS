<?php



/**
 * Datenbank Hilfsklasse
 * @package com.BuizCore
 * @subpackage SimFi
 */
abstract class DbAdmin
{

  /**
   * @var string
   */
  public $host = '127.0.0.1';

  /**
   * @var int
   */
  public $port = '5432';

  /**
   * @var string
   */
  public $user = '';

  /**
   * @var string
   */
  public $passwd = '';

  /**
   * @var string
   */
  public $dbName = null;

  /**
   * @var string
   */
  public $schema = 'public';

  /**
   * @var Db_Connection
   */
  public $con = null;

  /**
   * @var ProtocolWriter
   */
  public $protocol = null;


  /**
   * @param UiConsole $console
   * @param string $dbName
   * @param string $user
   * @param string $passwd
   * @param string $host
   * @param string $port
   * @param string $schema
   */
  public function __construct
  (
    $console,
    $dbName,
    $user,
    $passwd,
    $host = '127.0.0.1',
    $port = '5432',
    $schema = 'public'
  )
  {

    $this->console = $console;
    $this->dbName = $dbName;
    $this->user   = $user;
    $this->passwd = $passwd;
    $this->port   = $port;
    $this->schema = $schema;

  }//end public function __construct */


  /**
   * Login Variablen ins Environment schreiben
   * @param string $user
   * @param string $pwd
   * @return boolean
   */
   abstract public function setLoginEnv( $user, $pwd  );

////////////////////////////////////////////////////////////////////////////////
// Protocol
////////////////////////////////////////////////////////////////////////////////

   /**
    * @param ProtocolWriter $protocol
    */
   public function setProtocol( $protocol )
   {
     $this->protocol = $protocol;
   }//end public function setProtocol */

////////////////////////////////////////////////////////////////////////////////
// group
////////////////////////////////////////////////////////////////////////////////


  /**
   * Eine neue Gruppe in der Datenbank erstellen
   * @param string $group
   * @return boolean
   */
  abstract public function createGroup( $group );

  /**
   * Prüfen ob eine bestimmte Gruppe schon existiert
   * @param string $group
   * @return boolean
   */
  abstract public function groupExists( $group );

////////////////////////////////////////////////////////////////////////////////
// User
////////////////////////////////////////////////////////////////////////////////

  /**
   * Neuen User erstellen
   * @param string $user
   * @param string $pwd
   * @return boolean
   */
  abstract public function createUser( $user, $pwd, $type = null );

  /**
   * Einen User mit passenden Rechten fürs Backend erstellen
   * @param string $user
   * @param string $pwd
   * @return boolean
   */
  abstract public function createBackendUser( $user, $pwd );

  /**
   * Einen User mit rechten fürs Frontend erstellen
   * @param string $user
   * @param string $pwd
   * @return boolean
   */
  abstract public function createFrontendUser( $user, $pwd );

  /**
   * Einen Adminuser erstellen
   * @param string $user
   * @param string $pwd
   * @return boolean
   */
  abstract public function createAdminUser( $user, $pwd );

  /**
   * Prüfen ob ein user bereits existiert
   * @param string $user
   * @return boolean
   */
  abstract public function userExists( $user );

////////////////////////////////////////////////////////////////////////////////
// Database
////////////////////////////////////////////////////////////////////////////////

  /**
   * Erstellen einer neuen Datenbank
   * @param string $dbName
   * @param string $owner
   * @param string $encoding
   * @return boolean
   */
  abstract public function createDatabase(  $dbName, $owner, $encoding = "utf-8"  );

  /**
   * Prüfen ob eine Datenbank nicht bereits existiert
   * @param string $dbName
   * @return boolean
   */
  abstract public function databaseExists(  $dbName  );


  /**
   * Datenbank umbenennen
   * @param string $oldName
   * @param string $newName
   */
  abstract public function renameDatabase( $oldName, $newName   );

  /**
   * Prüfen ob eine Datenbank nicht bereits existiert
   * @param string $dbName
   * @return boolean
   */
  abstract public function dropDatabase(  $dbName  );

////////////////////////////////////////////////////////////////////////////////
// Schema
////////////////////////////////////////////////////////////////////////////////

  /**
   * Bestz
   * @param string $dbName
   * @param string $schema
   * @param string $owner
   */
  abstract public function createSchema( $dbName, $schema, $owner   );

  /**
   * Bestz
   * @param string $dbName
   * @param string $schema
   * @param string $owner
   */
  abstract public function dropSchema( $dbName, $schema );


  /**
   * @param string $dbName
   * @param string $schema
   */
  abstract public function schemaExists( $dbName, $schema  );

  /**
   * @param string $dbName
   * @param string $schema
   */
  abstract public function renameSchema( $dbName, $oldName, $newName  );

////////////////////////////////////////////////////////////////////////////////
// Sequence
////////////////////////////////////////////////////////////////////////////////

  /**
   * Prüfen ob eine bestimmtes schema bereits existiert
   * @param string $dbName
   * @param string $schemaName
   * @param string $sequence
   */
  abstract public function sequenceExists( $dbName, $schemaName, $sequence );

  /**
   * @param string $dbName
   * @param string $dbSchema
   * @param string $name
   * @param string $increment
   * @param string $start
   * @param string $minValue
   * @param string $maxValue
   *
   * @return boolean
   */
  abstract public function createSequence
  (
    $dbName,
    $dbSchema,
    $name,
    $owner = null,
    $increment = 1,
    $start = 1,
    $minValue = null,
    $maxValue = null
  );

  /**
   * @param array $gateways
   * @param string $deployPath
   */
  abstract public function syncDatabase( $gateways, $deployPath );

  /**
   * Eine Query absetzen
   * @param string $query
   * @param string $dbName
   * @param string $user
   * @param string $passwd
   */
  abstract public function query( $query, $dbName, $user = null, $passwd = null );

  /**
   * Erstellen eines Dumps für das aktuelle Schema
   * @param string $dbName
   * @param string $schema
   * @param string $dumpFile
   */
  abstract public function dumpSchema( $dbName, $schema, $dumpFile );

  /**
   * Erstellen eines Dumps für das aktuelle Schema
   * @param string $dbName
   * @param string $schema
   * @param string $dumpFile
   * @param string $dumpSchema nur nötig wenn das schema nach dem import umbenannt werden soll
   */
  abstract public function restoreSchema( $dbName, $schema, $dumpFile, $dumpSchema = null );

  /**
   * @param string $scriptPath
   * @param string $dbConf
   * @param string $tmpName
   */
  public function createImportFile( $scriptPath, $dbConf, $tmpName )
  {

    file_put_contents
    (
      $tmpName,
      str_replace
      (
        array( '{@schema@}','{@owner@}' ),
        array( $dbConf['schema'], $dbConf['owner'] ),
        file_get_contents( $scriptPath )
      )
    );

  }//end public function createImportFile */


}//end class DbAdminPostgresql
