<?php



/**
 * Datenbank Hilfsklasse
 * 
 * @subpackage web_expert
 */
class DbAdminPostgresql extends DbAdmin
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

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

////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////

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
   * @param DbPostgresql $con
   */
  public function setConnection( $con )
  {
    $this->con = $con;
  }//end public function setConnection */

  /**
   * Login Variablen ins Environment schreiben
   */
  public function setLoginEnv( $user, $pwd  )
  {
    // der root user muss vorhanden sein
    putenv( "PGUSER={$user}" );
    putenv( "PGPASSWORD={$pwd}" );

  }//end public function setLoginEnv */

  /**
   * @param string $group
   */
  public function createGroup( $group )
  {

    $sql = '"SELECT 1 FROM pg_roles WHERE rolname=\''.$group.'\'"';

    // wenn eine connection vorhanden ist verwenden wir die
    $this->setLoginEnv( $this->user, $this->passwd  );

    $val = Process::execute
    (
      'psql postgres -h '.$this->host.' -tAc '.$sql
    );

    if( '1' == trim($val) )
      return true;

    $val = Process::execute( 'psql postgres -h '.$this->host.' -tAc "CREATE ROLE '.$group.'"' );

    if( 'CREATE ROLE' == trim($val) )
      return true;
    else
      return false;

  }//end public function createGroup */

  /**
   * @param string $group
   */
  public function groupExists( $group )
  {

    $this->setLoginEnv( $this->user, $this->passwd  );

    $val = Process::execute
    (
      'psql postgres -h '.$this->host.' -tAc "SELECT 1 FROM pg_roles WHERE rolname=\''.$group.'\'"'
    );

    if( '1' == trim($val) )
      return true;

    return false;

  }//end public function groupExists */

////////////////////////////////////////////////////////////////////////////////
// Database
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $user
   * @param string $pwd
   * @param string $type
   */
  public function createUser( $user, $pwd, $type = null )
  {

    $this->setLoginEnv( $this->user, $this->passwd  );

    $val = Process::execute
    (
      'psql postgres -h '.$this->host.' -tAc "SELECT 1 FROM pg_roles WHERE rolname=\''.$user.'\'"'
    );

    if( '1' == trim($val) )
      return true;

    $val = Process::execute( 'psql postgres -h '.$this->host.' -tAc "CREATE USER '.$user.' with password \''.$pwd.'\'"' );

    if( 'CREATE ROLE' == trim($val) )
    {
      if( $this->protocol )
        $this->protocol->info( "Lege Db User: ".$user." an" );

      return true;
    }
    else
    {
      if( $this->protocol )
        $this->protocol->error( "Konnte den DB user: ".$user." nicht erstellen: ".$val );

      return false;
    }

  }//end public function createUser */

  /**
   * @param string $user
   * @param string $pwd
   */
  public function createBackendUser( $user, $pwd )
  {

    $this->setLoginEnv( $this->user, $this->passwd  );

    $val = Process::execute( 'psql postgres -h '.$this->host.' -tAc "SELECT 1 FROM pg_roles WHERE rolname=\''.$user.'\'"' );

    if( '1' == trim($val) )
      return true;

    $val = Process::execute( 'psql postgres -h '.$this->host.' -tAc "CREATE USER '.$user.' with password \''.$pwd.'\'"' );

    if( 'CREATE ROLE' == trim($val) )
    {
      if( $this->protocol )
        $this->protocol->info( "Lege Backend Db User: ".$user." an" );

      return true;
    }
    else
    {
      if( $this->protocol )
        $this->protocol->error( "Konnte den Backend DB user: ".$user." nicht erstellen: ".$val );

      return false;
    }

  }//end public function createBackendUser */

  /**
   * @param string $user
   * @param string $pwd
   */
  public function createFrontendUser( $user, $pwd )
  {

    $this->setLoginEnv( $this->user, $this->passwd  );

    $val = Process::execute( 'psql postgres -h '.$this->host.' -tAc "SELECT 1 FROM pg_roles WHERE rolname=\''.$user.'\'"' );

    if( '1' == trim($val) )
      return true;

    $val = Process::execute( 'psql postgres -h '.$this->host.' -tAc "CREATE USER '.$user.' with password \''.$pwd.'\'"' );

    if( 'CREATE ROLE' == trim($val) )
    {
      if( $this->protocol )
        $this->protocol->info( "Lege Frontend Db User: ".$user." an" );

      return true;
    }
    else
    {
      if( $this->protocol )
        $this->protocol->error( "Konnte den Frontend DB user: ".$user." nicht erstellen: ".$val );

      return false;
    }

  }//end public function createFrontendUser */

  /**
   * @param string $user
   * @param string $pwd
   */
  public function createAdminUser( $user, $pwd )
  {

    $this->setLoginEnv( $this->user, $this->passwd  );

    $val = Process::execute( 'psql postgres -h '.$this->host.' -tAc "SELECT 1 FROM pg_roles WHERE rolname=\''.$user.'\'"' );

    if( '1' == trim($val) )
      return true;

    $val = Process::execute( 'psql postgres -h '.$this->host.' -tAc "CREATE USER '.$user.' with createuser createdb password \''.$pwd.'\'"' );

    if( 'CREATE ROLE' == trim($val) )
    {
      if( $this->protocol )
        $this->protocol->info( "Lege Admin Db User: ".$user." an" );

      return true;
    }
    else
    {
      if( $this->protocol )
        $this->protocol->error( "Konnte den Admin DB user: ".$user." nicht erstellen: ".$val );

      return false;
    }

  }//end public function createAdminUser */

  /**
   * Prüfen ob ein user bereits existiert
   * @param string $user
   */
  public function userExists( $user )
  {

    $this->setLoginEnv( $this->user, $this->passwd  );

    $val = Process::execute( 'psql postgres -h '.$this->host.' -tAc "SELECT 1 FROM pg_roles WHERE rolname=\''.$user.'\'"' );

    if( '1' == trim($val) )
      return true;
    else
      return false;

  }//end public function userExists */

////////////////////////////////////////////////////////////////////////////////
// Database
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $dbName
   * @param string $owner
   * @param string $encoding
   */
  public function createDatabase(  $dbName, $owner, $encoding = "utf-8"  )
  {

    $this->setLoginEnv( $this->user, $this->passwd  );

    $val = Process::execute( 'psql postgres -h '.$this->host.' -tAc "SELECT 1 FROM pg_database WHERE datname=\''.$dbName.'\'"' );

    if( '1' == trim($val) )
      return true;

    $val = Process::execute
    (
      'psql postgres -h '.$this->host.' -tAc "CREATE DATABASE '.$dbName
      .' with owner  '.$owner
      .' encoding \''.$encoding.'\'"'
    );


    if( 'CREATE DATABASE' == trim($val) )
    {

      if( $this->protocol )
        $this->protocol->info( "Erstelle Datenbank: ".$dbName." " );

      // plpgsql language erstellen
      $val = Process::execute
      (
        'psql '.$dbName.' -h '.$this->host.' -tAc "CREATE LANGUAGE plpgsql;"'
      );

      return true;
    }
    else
    {

      if( $this->protocol )
        $this->protocol->info( "Das Erstellen der Datenbank: ".$dbName." ist fehlgeschlagen: ".$val );

      return false;
    }

  }//end public function createDatabase */

  /**
   * Prüfen ob eine Datenbank nicht bereits existiert
   * @param string $dbName
   */
  public function databaseExists(  $dbName  )
  {

    $this->setLoginEnv( $this->user, $this->passwd  );

    $val = Process::execute
    (
      'psql postgres -h '.$this->host.' -tAc "SELECT 1 FROM pg_database WHERE datname=\''.$dbName.'\'"'
    );

    if( '1' == trim($val) )
      return true;
    else
      return false;

  }//end public function databaseExists */

  /**
   * Prüfen ob eine Datenbank nicht bereits existiert
   * @param string $oldName
   * @param string $newName
   */
  public function renameDatabase(  $oldName, $newName  )
  {

    $this->setLoginEnv( $this->user, $this->passwd  );

    $val = Process::execute
    (
      'psql postgres -h '.$this->host.' -tAc "ALTER DATABASE '.$oldName.' RENAME TO '.$newName.';"'
    );

    if( 'ALTER DATABSE' == trim($val) )
      return true;
    else
      return false;

  }//end public function renameDatabase */

  /**
   * löschen eine Datenbank
   * @param string $dbName
   */
  public function dropDatabase(  $dbName  )
  {

    $this->setLoginEnv( $this->user, $this->passwd  );

    $val = Process::execute
    (
      'psql postgres -h '.$this->host.' -tAc "DROP DATABASE '.$dbName.' CASCADE;"'
    );

    if( 'DROP DATABSE' == trim($val) )
      return true;
    else
      return false;

  }//end public function dropDatabase */


  /**
   * @param string $dbName
   * @param string $owner
   *
   * @return boolean
   */
  public function chownDbCascade( $dbName, $owner )
  {

    $schemas = $this->getSchemas();

    $this->chownDb( $dbName, $owner );

    foreach( $schemas as $schema  )
    {
      $this->chownSchemaCascade( $dbName, $schema['schema_name'], $owner );
    }


  }//end public function schemaExists */

  /**
   * @param string $dbName
   * @param string $owner
   */
  public function chownDb( $dbName, $owner )
  {

    $sql = <<<SQL
ALTER DATABASE {$dbName} OWNER TO {$owner};

SQL;

    return $this->ddl( $sql );

  }//end public function chownDb */

////////////////////////////////////////////////////////////////////////////////
// Schema
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $dbName
   * @return array
   */
  public function getSchemas( $dbName  )
  {

    $sql = <<<SQL
  SELECT
    ns.nspname as schema_name,
    db.datname as db_name
    FROM
    pg_namespace as ns
    join
      pg_database db on ns.nspowner = db.datdba
SQL;

    if( $dbName )
    {
      $sql .= <<<SQL
    WHERE db.datname = '{$dbName}'
SQL;
    }

    $sql .= ";";

    return $this->con->select( $sql );

  }//end public function getSchemas */

  /**
   * @param string $dbName
   * @param string $schema
   * @param string $owner
   *
   * @return boolean
   */
  public function createSchema( $dbName, $schema, $owner   )
  {

    $this->setLoginEnv( $this->user, $this->passwd  );

    $val = Process::execute
    (
      'psql '.$dbName.' -h '.$this->host.' -tAc "SELECT 1 FROM information_schema.schemata WHERE catalog_name=\''.$dbName.'\' and schema_name=\''.$schema.'\';"'
    );

    if( '1' == trim($val) )
      return true;

    $val = Process::execute
    (
      'psql '.$dbName.' -h '.$this->host.' -tAc "CREATE SCHEMA '.$schema
      .' AUTHORIZATION '.$owner.';"'
    );

    if( 'CREATE SCHEMA' == trim($val) )
      return true;
    else
    {
      UiConsole::debugLine( $val );
      return false;
    }

  }//end public function createSchema */

  /**
   * @param string $dbName
   * @param string $schema
   *
   * @return boolean
   */
  public function schemaExists( $dbName, $schema   )
  {

    $this->setLoginEnv( $this->user, $this->passwd  );

    UiConsole::debugLine( 'psql '.$dbName.' -h '.$this->host.' -tAc "SELECT 1 FROM information_schema.schemata WHERE catalog_name=\''.$dbName.'\' and schema_name=\''.$schema.'\';"' );

    $val = Process::execute
    (
      'psql  '.$dbName.' -h '.$this->host.' -tAc "SELECT 1 FROM information_schema.schemata WHERE catalog_name=\''.$dbName.'\' and schema_name=\''.$schema.'\';"'
    );

    if( '1' == trim($val) )
    {
      return true;
    }
    else
    {
      UiConsole::debugLine( $val );
      return false;
    }

  }//end public function schemaExists */

  /**
   * @param string $dbName
   * @param string $schema
   *
   * @return boolean
   */
  public function dropSchema( $dbName, $schema   )
  {

    $this->setLoginEnv( $this->user, $this->passwd  );

    $val = Process::execute( 'psql '.$dbName.' -h '.$this->host.' -tAc "DROP SCHEMA '.$schema.' CASCADE;"' );

    if( 'DROP SCHEMA' == trim($val) )
      return true;
    else
      return false;

  }//end public function schemaExists */

  /**
   * @param string $dbName
   * @param string $oldName
   * @param string $newName
   *
   * @return boolean
   */
  public function renameSchema( $dbName, $oldName, $newName   )
  {

    $this->setLoginEnv( $this->user, $this->passwd  );

    $val = Process::execute( 'psql '.$dbName.' -h '.$this->host.' -tAc "ALTER SCHEMA '.$oldName.' RENAME TO '.$newName.';"' );

    if( 'ALTER SCHEMA' == trim($val) )
      return true;
    else
      return false;

  }//end public function renameSchema */

  /**
   * Den Besitzer eines Schemas ändern
   * @param string $schema
   * @param string $owner
   */
  public function chownSchema( $schema, $owner )
  {

    $sql = <<<SQL
ALTER SCHEMA {$schema} OWNER TO {$owner};

SQL;

    return $this->ddl( $sql );

  }//end public function chownSchema */

  /**
   * Den Besitzer eines Schemas ändern
   * @param string $dbName
   * @param string $schema
   * @param string $dbSchema
   */
  public function chownSchemaCascade( $dbName, $dbSchema, $owner )
  {

    $this->chownSchema( $dbSchema, $owner );

    $tables     = $this->getTables( $dbName, $dbSchema );
    $views      = $this->getViews( $dbName, $dbSchema );
    $sequences  = $this->getSequences( $dbName, $dbSchema );

    foreach( $tables as $table )
    {
      $this->chownTable( $dbName, $dbSchema, $table['name'], $owner );
    }

    foreach( $views as $view )
    {
      $this->chownView( $dbName, $dbSchema, $view['name'], $owner );
    }

    foreach( $sequences as $sequence )
    {
      $this->chownSequence( $dbName, $dbSchema, $sequence['name'], $owner );
    }

  }//end public function chownSchema */


////////////////////////////////////////////////////////////////////////////////
// Code Dumps
////////////////////////////////////////////////////////////////////////////////

  /**
   * Erstellen eines Dumps für das aktuelle Schema
   * @param string $dbName
   * @param string $schema
   * @param string $dumpFile
   */
  public function dumpSchema( $dbName, $schema, $dumpFile )
  {


    $command = '/usr/bin/pg_dump';

    $callParams = array();

    $callParams[] = '--host '.$this->host;
    $callParams[] = '--port '.$this->port;
    $callParams[] = '--username '.$this->user;
    $callParams[] = '--format custom';
    $callParams[] = '--verbose';
    $callParams[] = '--file "'.$dumpFile.'"';
    $callParams[] = '--schema '.$schema;
    $callParams[] = $dbName;

    Fs::touchFileFolder( $dumpFile );

    $callEnv = array();
    $callEnv['PGPASSWORD'] = $this->passwd;


    $dumpProcess = new ProcessRunner();
    if( !$dumpProcess->open( $command, $callParams, $callEnv ) )
    {
      return "Failed to Open command {$command}";
    }

    //$result = 'clear2';

    //$result = $dumpProcess->read();

    return $dumpProcess->close().' saved in '.$dumpFile;

  }//end public function dumpSchema */

  /**
   * Wiederherstellen eines Datenbankdumps
   *
   * @param string $dbName
   * @param string $schema
   * @param string $dumpFile
   * @param string $schemaDump
   */
  public function restoreSchema( $dbName, $schema, $dumpFile, $schemaDump = null )
  {

    if( !file_exists( $dumpFile ) )
    {
      throw new WebExpertException( 'Missing dump '.$dumpFile );
    }

    // wenn das Schema bereits existiert, das vorhandene umbenennen
    // um keine daten zu verlieren
    if( $this->schemaExists( $dbName, $schema ) )
    {
      $this->renameSchema( $dbName, $schema, $schema.'_bckbfrst_'.date('YmdHis') );
    }


    $command = '/usr/bin/pg_restore';

    $callParams = array();

    $callParams[] = '--host '.$this->host;
    $callParams[] = '--port '.$this->port;
    $callParams[] = '--username '.$this->user;
    $callParams[] = '--format custom';
    $callParams[] = '--verbose';
    //$callParams[] = '--schema '.$schema;
    $callParams[] = $dbName;
    $callParams[] = '"'.$dumpFile.'"';

    $callEnv = array();
    $callEnv['PGPASSWORD'] = $this->passwd;

    $dumpProcess = new ProcessRunner();
    if( !$dumpProcess->open( $command, $callParams, $callEnv ) )
    {
      throw new WebExpertException( "Failed to Open command {$command}" );
    }
    else
    {

      if( $this->protocol )
        $this->protocol->info( $dumpProcess->read() );

    }

    // Sicher stellen, dass das Schema auch mit dem richtigen Namen
    // importiert wurde
    if( $schemaDump && $schemaDump != $schema  )
    {
      $this->renameSchema( $dbName, $schemaDump, $schema );
    }

    //$result = 'clear2';
    //$result = $dumpProcess->read();

    return $dumpProcess->close().' restored dump '.$dumpFile;

  }//end public function restoreSchema */

////////////////////////////////////////////////////////////////////////////////
//
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $dbName
   * @param string $schemaName
   * @param string $sequence
   *
   * @return boolean
   */
  public function sequenceExists( $dbName, $schemaName, $sequence   )
  {

    $this->setLoginEnv( $this->user, $this->passwd  );

    $query = "SELECT 1 FROM  pg_class cl "
      ." JOIN pg_namespace ns ON ns.oid = cl.relnamespace "
      ." WHERE relkind = 'S' and relname = '{$sequence}' and ns.nspname = '{$schemaName}';";

    $val = Process::execute( 'psql '.$dbName.' -h '.$this->host.' -tAc "'.$query.'"' );

    if( '1' == trim($val) )
      return true;
    else
      return false;

  }//end public function sequenceExists */

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
  public function createSequence
  (
    $dbName,
    $dbSchema,
    $sequence,
    $owner = null,
    $increment = 1,
    $start = 1,
    $minValue = null,
    $maxValue = null
  )
  {

    if( !$owner )
      $owner = $this->user;

    $this->setLoginEnv( $this->user, $this->passwd );

    $query = "SELECT 1 FROM  pg_class cl "
      ." JOIN pg_namespace ns ON ns.oid = cl.relnamespace "
      ." WHERE relkind = 'S' and relname = '{$sequence}' and ns.nspname = '{$dbSchema}';";

    $val = Process::execute( 'psql '.$dbName.' -h '.$this->host.' -tAc "'.$query.'"' );

    if( '1' == trim($val) )
    {
      UiConsole::debugLine( "Tried to create allready existing sequence {$sequence}" );
      return true;
    }

    $query = "CREATE SEQUENCE {$dbSchema}.{$sequence} INCREMENT {$increment} START {$start};";

    $val = Process::execute( 'psql '.$dbName.' -h '.$this->host.' -tAc "'.$query.'"' );

    if( 'CREATE SEQUENCE' == trim($val) )
    {

      if( $this->protocol )
        $this->protocol->info( "Erstelle Sequence: {$sequence} in {$dbName}.{$dbSchema}." );

      Process::execute( 'psql '.$dbName.' -h '.$this->host.' -tAc "ALTER TABLE '.$dbSchema.'.'.$sequence.' OWNER TO '.$owner.';"' );
      return true;
    }
    else
    {
      if( $this->protocol )
        $this->protocol->error( "Erstelle der Sequence: {$sequence} in {$dbName}.{$dbSchema} ist fehlgeschlagen. ".$val );

      UiConsole::debugLine( $val );
      return false;
    }

  }//end public function createSequence */

  /**
   * @param string $dbName
   * @param string $dbName
   * @return array Liste aller vorhandenen Sequenzen
   */
  public function getSequences( $dbName, $dbSchema )
  {

    $sql = <<<SQL
SELECT
  cl.oid,
  relname as name,
  pg_get_userbyid(relowner) AS owner,
  relacl,
  description
FROM
  pg_class cl
LEFT OUTER JOIN
  pg_description des ON des.objoid=cl.oid
JOIN
  pg_namespace ns
    ON ns.oid = cl.relnamespace

 WHERE
   relkind = 'S'
     AND ns.nspname  = '{$dbSchema}'
 ORDER BY relname

SQL;

    $sql .= ";";

    return $this->con->select( $sql );

  }//end public function getSequences */

  /**
   * Den Besitzer einer Tabelle ändern
   * @param string $dbName
   * @param string $schema
   * @param string $table
   * @param string $owner
   */
  public function chownSequence( $dbName, $dbSchema, $table, $owner )
  {

    $sql = <<<SQL
ALTER TABLE {$dbSchema}.{$table} OWNER TO {$owner};

SQL;

    return $this->ddl( $sql );

  }//end public function chownSequence */

////////////////////////////////////////////////////////////////////////////////
// Table Code
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $dbName
   * @param string $schemaName
   */
  public function getTables( $dbName, $schemaName )
  {

    $sql = <<<SQL
  SELECT
    table_name as name
    FROM  information_schema.tables
    WHERE
    table_catalog = '{$dbName}'
    AND table_schema = '{$schemaName}'
    AND table_type  = 'BASE TABLE'
    ORDER BY table_name ;
SQL;

    return $this->con->select( $sql );

  }//end public function getTables */

  /**
   * Den Besitzer einer Tabelle ändern
   * @param string $dbName
   * @param string $dbSchema
   * @param string $table
   * @param string $owner
   */
  public function chownTable( $dbName, $dbSchema, $table, $owner )
  {

    $sql = <<<SQL
ALTER TABLE {$dbSchema}.{$table} OWNER TO {$owner};

SQL;

    return $this->ddl( $sql );

  }//end public function chownTable */

  /**
   * Eine bestimmte Tabelle löschen
   * @param string $dbName
   * @param string $dbSchema
   * @param string $table
   * @param boolean $cascade
   */
  public function dropTable( $dbName, $dbSchema, $table, $cascade = true )
  {

    $codeCascade = $cascade?' CASCADE':'';

    $sql = <<<SQL
DROP TABLE {$dbSchema}.{$table} {$codeCascade};

SQL;

    return $this->ddl( $sql );

  }//end public function dropTable */

  /**
   * @param string $dbName
   * @param string $schemaName
   * @param string $tableName
   */
  public function getTableIndices( $dbName, $schemaName, $tableName )
  {

    $sql = <<<SQL
SELECT DISTINCT
ON(cls.relname) cls.oid,
cls.relname as idxname,
indrelid,
indkey,
indisclustered,
indisunique,
indisprimary,
n.nspname,
indnatts,
cls.reltablespace AS spcoid,
spcname,
tab.relname as tabname,
indclass,
con.oid AS conoid,
CASE contype
WHEN 'p' THEN desp.description
WHEN 'u' THEN desp.description
ELSE des.description
END AS description,
pg_get_expr(indpred, indrelid, true) as indconstraint,
contype,
condeferrable,
condeferred,
amname,
substring(array_to_string(cls.reloptions, ',') from 'fillfactor=([0-9]*)') AS fillfactor
  FROM pg_index idx
  JOIN pg_class cls ON cls.oid=indexrelid
  JOIN pg_class tab ON tab.oid=indrelid
  LEFT OUTER JOIN pg_tablespace ta on ta.oid=cls.reltablespace
  JOIN pg_namespace n ON n.oid=tab.relnamespace
  JOIN pg_am am ON am.oid=cls.relam
  LEFT JOIN pg_depend dep ON (dep.classid = cls.tableoid AND dep.objid = cls.oid AND dep.refobjsubid = '0')
  LEFT OUTER JOIN pg_constraint con ON (con.tableoid = dep.refclassid AND con.oid = dep.refobjid)
  LEFT OUTER JOIN pg_description des ON des.objoid=cls.oid
  LEFT OUTER JOIN pg_description desp ON (desp.objoid=con.oid AND desp.objsubid = 0)
 WHERE
    n.nspname = '{$schemaName}'
   AND tab.relname = '{$tableName}'

 ORDER BY cls.relname;
SQL;

    return $this->con->select( $sql );

  }//end public function getTableIndices */

  /**
   * @param string $dbName
   * @param string $schemaName
   * @param string $tableName
   * @param string $indexName
   */
  public function tableIndexExists( $dbName, $schemaName, $tableName, $indexName )
  {

    $sql = <<<SQL
SELECT
  count( cls.relname ) as num
FROM
  pg_index idx
JOIN
  pg_class cls ON cls.oid=indexrelid
JOIN
  pg_class tab ON tab.oid=indrelid
LEFT OUTER JOIN
  pg_tablespace ta on ta.oid=cls.reltablespace
JOIN
  pg_namespace n ON n.oid=tab.relnamespace
JOIN
  pg_am am ON am.oid=cls.relam
LEFT JOIN
  pg_depend dep ON (dep.classid = cls.tableoid AND dep.objid = cls.oid AND dep.refobjsubid = '0')
LEFT OUTER JOIN
  pg_constraint con ON (con.tableoid = dep.refclassid AND con.oid = dep.refobjid)
WHERE
  n.nspname = '{$schemaName}'
    AND tab.relname = '{$tableName}'
    AND cls.relname = '{$indexName}' ;
SQL;

    return (boolean)$this->con->select( $sql, 'num' );

  }//end public function tableIndexExists */

  /**
   * @param string $dbName
   * @param string $schemaName
   * @param string $indexName
   */
  public function dropTableIndex( $dbName, $schemaName, $indexName )
  {

    return $this->con->ddl( "DROP INDEX {$schemaName}.{$indexName};" );

  }//end public function dropTableIndex */

////////////////////////////////////////////////////////////////////////////////
//
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $dbName
   * @param string $dbSchema
   *
   * @return array liste der Views
   */
  public function getViews( $dbName, $dbSchema )
  {

    $sql = <<<SQL
SELECT
c.oid,
c.xmin,
c.relname as name,
pg_get_userbyid(c.relowner) AS owner,
na.nspname as schema_name,
c.relacl,
description,
pg_get_viewdef(c.oid, true) AS definition
  FROM pg_class c
  LEFT OUTER JOIN pg_description des ON (des.objoid=c.oid and des.objsubid=0)
  JOIN pg_namespace na ON na.oid = c.relnamespace
WHERE ((c.relhasrules AND (EXISTS (
           SELECT r.rulename FROM pg_rewrite r
            WHERE ((r.ev_class = c.oid)
              AND (bpchar(r.ev_type) = '1'::bpchar)) ))) OR (c.relkind = 'v'::char))
  AND na.nspname = '{$dbSchema}'
ORDER BY relname;

SQL;

    $sql .= ";";

    return $this->con->select($sql);

  }//end public function getViews */

  /**
   * Den Besitzer einer Tabelle ändern
   * @param string $dbName
   * @param string $schema
   * @param string $table
   * @param string $owner
   */
  public function chownView( $dbName, $dbSchema, $table, $owner )
  {

    $sql = <<<SQL
ALTER TABLE {$dbSchema}.{$table} OWNER TO {$owner};

SQL;

    return $this->ddl( $sql );

  }//end public function chownView */

  /**
   * Eine View löschen
   * Kaskadierendes löschen ist default
   * @param string $dbName
   * @param string $dbSchema
   * @param string $viewName
   * @param boolean $cascade
   */
  public function dropView( $dbName, $dbSchema, $viewName, $cascade = true )
  {

    $codeCascade = $cascade?' CASCADE':'';

    $sql = <<<SQL
DROP VIEW {$dbSchema}.{$viewName} {$codeCascade};

SQL;

    return $this->ddl( $sql );

  }//end public function dropView */

  /**
   * Alle Views eines Schemas löschen
   * @param string $dbName
   * @param string $dbSchema
   */
  public function dropSchemaViews( $dbName, $dbSchema )
  {

    $views = $this->getViews( $dbName, $dbSchema );

    foreach( $views as $view )
    {

      // könnte wegen dem cascade löschen passieren
      if( !$this->viewExists( $dbName, $dbSchema, $view['name'] ) )
        continue;

      $sql = <<<SQL
DROP VIEW {$dbSchema}.{$view['name']} CASCADE;

SQL;

      $this->ddl( $sql );
    }

  }//end public function dropSchemaViews */

  /**
   * @param string $dbName
   * @param string $schemaName
   * @param string $viewName
   *
   */
  public function viewExists( $dbName, $schemaName, $viewName )
  {

    $sql = <<<SQL
  SELECT
    count(table_schema) as num
    FROM  information_schema.tables
    WHERE
    table_catalog = '{$dbName}'
    AND table_schema = '{$schemaName}'
    AND table_name = '{$viewName}'
    AND table_type  = 'VIEW'  ;
SQL;

    return $this->con->select($sql,'num') ? true:false;

  }//end public function viewExists */

////////////////////////////////////////////////////////////////////////////////
//
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param array $gateways
   * @param string $deployPath
   */
  public function syncDatabase( $gateways, $deployPath )
  {

    $this->setLoginEnv( $this->user, $this->passwd  );

    foreach( $gateways as $gatewayProject )
    {
      Protocol::info( "Sync Database: ".'bash ./sync_database.sh "'.$deployPath.$gatewayProject['name'].'" "'.$deployPath.'"'  );
      Fs::chdir( WEBX_PATH );
      Process::run( 'bash ./sync_database.sh "'.$deployPath.$gatewayProject['name'].'" "'.$deployPath.'"' );
    }

    Fs::chdir( WEBX_PATH );

  }//end public function syncDatabase */

  /**
   * @param string $scriptPath
   * @param string $dbName
   * @param string $dbSchema
   * @param string $owner
   */
  public function importStructureFile( $scriptPath, $dbName, $schemaName, $owner )
  {

    if( !Fs::exists( $scriptPath ) )
    {
      throw new WebExpertException( "Habe nicht existierendes DB Script zum importieren bekommen ".$scriptPath );
    }

    if( $this->con )
    {
      $error = null;
      if( WebExpert::checkSyntax( $scriptPath, $error ) )
        include $scriptPath;
      else
        throw new WebExpertException( "Syntax for Datafile: {$scriptPath} is invalid ".$error );

      return;
    }

    $tmpFileName = WebExpert::tmpFile( 'db_dump' );

    Fs::touch($tmpFileName);
    file_put_contents
    (
      $tmpFileName,
      'SET SEARCH_PATH = '.$schemaName."; \n".str_replace
      (
        array( '{@db@}','{@schema@}','{@owner@}' ),
        array( $dbName, $schemaName, $owner ),
        file_get_contents( $scriptPath )
      )
    );

    Process::execute( 'psql '.$dbName.' -h '.$this->host.' -f  '.$tmpFileName );

    Fs::del( $tmpFileName );

  }//end public function importStructureFile */


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

  /**
   * Analysieren der Rückgabe auf Fehler
   * @param string $msg
   */
  public function searchError( $msg )
  {

    if( false !== strpos($msg, 'FATAL:  Ident-Authentifizierung') )
      throw new DbException
      (
        'Die Datenbank hat den Login verweigert. Dafür kann es mehrer Möglichkeiten geben.
        Der Benutzer ist falsch geschrieben, existiert nicht, das Password könnte falsch sein,
        oder in der pg_hba.conf ist für local indent anstelle von md5 eingetragen.'
      );

    // kein fehler gefunden
    return null;

  }//end public function searchError */

  /**
   * @param string $query
   * @param string $dbName
   * @param string $user
   * @param string $passwd
   */
  public function query( $query, $dbName = null, $user = null, $passwd = null )
  {

    $this->setLoginEnv( $this->user, $this->passwd  );
    return Process::execute( 'psql '.$dbName.' -h '.$this->host.' -tAc "'.$query.'"' );

  }//end public function query */

  /**
   * @param string $query
   */
  public function ddl( $query )
  {

    $this->con->ddl( $query );

  }//end public function ddl */


}//end class DbAdminPostgresql
