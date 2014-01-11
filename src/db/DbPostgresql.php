<?php



/**
 * Datenbank Hilfsklasse
 * @package com.BuizCore
 * @subpackage SimFi
 */
class DbPostgresql extends Db_Connection
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
   * Die Connection Resource
   * @var resource
   */
  protected $connection = null;

  /**
   * Das DB Admin Objekt
   * @var DbAdmin
   */
  protected $dbAdmin = null;

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
  ) {

    $this->dbName = $dbName;
    $this->user   = $user;
    $this->passwd = $passwd;

    if( is_null( $host ) )
      $host = '127.0.0.1';
    $this->host   = $host;

    if( is_null( $port ) )
      $port = '5432';
    $this->port   = $port;

    $this->schema = $schema;

  }//end public function __construct */

  /**
   * öffnen der datenbankverbindung
   */
  public function open()
  {

    $pgsql_con_string = 'host='.$this->host
      .' port='.$this->port
      .' dbname='.$this->dbName
      .' user='.$this->user
      .' password='.$this->passwd;

    if( !$this->connection = pg_connect( $pgsql_con_string ))
    {
      throw new DbException( 'Connection failed' );
    }

    if( $this->schema  )
    {
      $this->setSearchPath( $this->schema );
    }

  }//end public function open */

  /**
   * schliesen der datenbank verbindung
   */
  public function close()
  {

    if( is_resource(  $this->connection ) )
      pg_close( $this->connection );

  }//end public function close */

  /**
   * @return DbAdminPostgresql
   */
  public function getDbAdmin()
  {

    if( $this->dbAdmin )
      return $this->dbAdmin;

    $this->dbAdmin = new DbAdminPostgresql
    (
      UiConsole::getActive(),
      $this->dbName,
      $this->user,
      $this->passwd,
      $this->host,
      $this->port,
      $this->schema
    );
    $this->dbAdmin->con = $this;

    return $this->dbAdmin;

  }//end public function getDbAdmin */

  /**
   * Setzten des Aktiven Schemas
   *
   * @param string Schema Das aktive Schema
   * @return bool
   */
  public function setSearchPath( $schema )
  {

    $sqlstring = 'SET search_path = "'.$schema.'", pg_catalog;';


    if( !$result = pg_query( $this->connection , $sqlstring ) ) {
      // Fehlermeldung raus und gleich mal nen Trace laufen lassen
      throw new DbException(
        'Query '.$sqlstring.' Failed: '.pg_last_error( $this->connection )
      );
    }

    $this->schema = $schema;

    return true;
  } // end public function setSearchPath */

////////////////////////////////////////////////////////////////////////////////
// Query Logic
////////////////////////////////////////////////////////////////////////////////

  /**
   * de:
   * eine einfach select abfrage an die datenbank
   * select wird immer auf der lesende connection ausgeführt
   *
   * @param string $sql ein SQL String
   * @param string $singleRow
   * @param boolean $expectResult
   *
   * @return array/scalar
   * @throws DbException
   *  - bei inkompatiblen parametern
   */
  public function select( $sql, $singleRow = null, $expectResult = false  )
  {

    if( !is_resource($this->connection) ) {
      $this->open();
    }

    if( !$result = pg_query( $this->connection , $sql ) ) {
      // Fehlermeldung raus und gleich mal nen Trace laufen lassen
      throw new DbException
      (
        'Query '.$sql.' Failed: '.pg_last_error( $this->connection )
      );
    }

    $data = array();

    if( $singleRow ) {

       $row = pg_fetch_assoc( $result );

       if( !$row ) {
         if( $expectResult )
           throw new DbException( "Result was empty, but result was expected" );

         return array();
       }

      if( is_string($singleRow) ) {
        if( array_key_exists($singleRow, $row) )
          return $row[$singleRow];
        else
          throw new DbException( "Requested nonexisting Index ".$singleRow );
      }

      return $row;
    }

    while ( $row = pg_fetch_assoc( $result ) )
      $data[] = $row;


   if( !$data ) {
     if( $expectResult )
       throw new DbException( "Result was empty, but result was expected" );
   }

    return $data;

  } // end public function select */

  /**
   * de:
   * ausführen einer insert query
   *
   * @param mixed $sql
   * @param string $tableName
   * @param string $tablePk
   * @return int
   * @throws DbException im fehlerfall
   */
  public function insert( $sql, $tableName = null, $tablePk = null  )
  {


    if( !is_resource($this->connection) ) {
      $this->open();
    }

    if( !$result = pg_query( $this->connection , $sql ) )
    {
      throw new DbException
      (
        'Query '.$sql.' failed: '.pg_last_error( $this->connection )
      );
    }

    // das kann passieren, wenn eine insert if not exists query läuft
    // dann kann es dazu kommen, dass kein datensatz angelegt wird, also
    // wollen wir in dem kontext dann auch keine id zurückgeben
    if( !pg_affected_rows($result) )
      return null;

    //$sqlstring = 'select currval( \''.strtolower($tableName).'_'.strtolower($tablePk).'_seq\')';
    $sqlstring = "select currval('entity_oid_seq');";


    if( !$result = pg_query( $this->connection , $sqlstring) )
    {
      throw new DbException
      (
        'Failed to receive a new id: '.pg_last_error( $this->connection )
      );
    }

    $row = pg_fetch_row( $result );

    return $row[0];

  } // end public function insert */

  /**
   * Ein Updatestatement an die Datenbank schicken
   *
   * @param string $sql Ein Aktion Object
   * @throws DbException
   * @return int
   */
  public function update( $sql )
  {

    if( !is_resource($this->connection) )
    {
      $this->open();
    }

    if( !$result = pg_query( $this->connection, $sql ) )
    {
      // Fehlermeldung raus und gleich mal nen Trace laufen lassen
      throw new DbException
      (
        'Query '.$sql.' failed: '.pg_last_error( $this->connection )
      );
    }

    return pg_affected_rows( $result );

  }// end public function update */

  /**
   * Ein Delete Statement
   *
   * @param string $sql Ein Aktion Object
   * @throws DbException
   * @return int
   */
  public function delete( $sql )
  {

    if( !is_resource($this->connection) )
    {
      $this->open();
    }

    if( !$result = pg_query( $this->connection, $sql ) )
    {
      // Fehlermeldung raus und gleich mal nen Trace laufen lassen
      throw new DbException
      (
        'Query '.$sql.' failed: '.pg_last_error( $this->connection )
      );
    }

    return pg_affected_rows( $result );

  }// end public function delete */

  /**
   * Ein Updatestatement an die Datenbank schicken
   *
   * @param string $sql Ein Aktion Object
   * @throws DbException
   * @return int
   */
  public function ddl( $sql )
  {

    if( !is_resource($this->connection) )
    {
      $this->open();
    }

    if( !$result = pg_query( $this->connection, $sql ) )
    {
      // Fehlermeldung raus und gleich mal nen Trace laufen lassen
      throw new DbException
      (
        'Query '.$sql.' failed: '.pg_last_error( $this->connection )
      );
    }

  }// end public function ddl */

////////////////////////////////////////////////////////////////////////////////
// Sequence Code
////////////////////////////////////////////////////////////////////////////////

  /**
   * de:
   * ausführen einer insert query
   *
   * @param string $seqName
   * @return int
   * @throws DbException im fehlerfall
   */
  public function nextVal( $seqName  )
  {

    if( !is_resource($this->connection) )
    {
      $this->open();
    }

    $sqlstring = "select nextval('".$seqName."');";


    if( !$result = pg_query( $this->connection, $sqlstring ) )
    {
      throw new DbException
      (
        'No Db Result: '.pg_last_error( $this->connection ).' '.$sqlstring
      );
    }

    $row = pg_fetch_row( $result );
    return $row[0];

  } // end public function nextVal */

  /**
   * de:
   * ausführen einer insert query
   *
   * @param string $seqName
   * @return int
   * @throws DbException im fehlerfall
   */
  public function currVal( $seqName  )
  {

    if( !is_resource($this->connection) )
    {
      $this->open();
    }

    $sqlstring = "select currval('".$seqName."');";

    if( !$result = pg_query( $this->connection, $sqlstring ) )
    {
      throw new DbException
      (
        'No Db Result: '.pg_last_error( $this->connection ).' '.$sqlstring
      );
    }

    $row = pg_fetch_row( $result );
    return $row[0];

  } // end public function currVal */

  /**
   * de:
   * ausführen einer insert query
   *
   * @param string $seqName
   * @return int
   * @throws DbException im fehlerfall
   */
  public function lastVal( $seqName  )
  {

    if( !is_resource($this->connection) )
    {
      $this->open();
    }

    $sqlstring = "select lastval('".$seqName."');";

    if( !$result = pg_query( $this->connection, $sqlstring ) )
    {
      throw new DbException
      (
        'No Db Result: '.pg_last_error( $this->connection ).' '.$sqlstring
      );
    }

    $row = pg_fetch_row( $result );
    return $row[0];

  } // end public function lastVal */

////////////////////////////////////////////////////////////////////////////////
// Transaction Code
////////////////////////////////////////////////////////////////////////////////

  /**
   * Starten einer Transaktion
   *
   * @throws DbException
   */
  public function begin(  )
  {

    if( !is_resource($this->connection) )
    {
      $this->open();
    }

    if(! $result = pg_query( $this->connection , 'BEGIN' ) )
    {
      throw new DbException
      (
        'Fehler beim ausführen von Commit: '.pg_last_error( $this->connection )
      );
    }

  } // end public function begin */

  /**
   * Transaktion wegen Fehler abbrechen
   *
   * @throws DbException
   */
  public function rollback( )
  {

    if(! $result = pg_query( $this->connection , 'ROLLBACK' ) )
    {
      throw new DbException
      (
        'Fehler beim ausführen von Commit: '.pg_last_error( $this->connection )
      );
    }

  } // end public function rollback */

  /**
   * Transaktion erfolgreich Abschliesen
   *
   * @throws DbException
   */
  public function commit( )
  {

    if(! $result = pg_query( $this->connection , 'COMMIT' ) )
    {
      throw new DbException
      (
        'Fehler beim ausführen von Commit: '.pg_last_error( $this->connection )
      );
    }

  } // end public function commit */

}//end class DbPostgresql
