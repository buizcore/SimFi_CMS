<?php

/**
 * @package com.BuizCore
 * @subpackage SimFi
 */
class DbMysql extends Db_Connection
{

    /**
     *
     * @var string
     */
    public $host = '127.0.0.1';

    /**
     *
     * @var int
     */
    public $port = '3306';

    /**
     *
     * @var string
     */
    public $user = '';

    /**
     *
     * @var string
     */
    public $passwd = '';

    /**
     *
     * @var string
     */
    public $dbName = null;

    /**
     * Die Connection Resource
     * 
     * @var mysqli
     */
    protected $connection = null;

    /**
     * Das DB Admin Objekt
     * 
     * @var DbAdmin
     */
    protected $dbAdmin = null;

    /**
     *
     * @var unknown
     */
    protected $escapeTypes = array(
        'text' => true,
        'date' => true,
        'time' => true
    );

    /**
     *
     * @param string $dbName            
     * @param string $user            
     * @param string $passwd            
     * @param string $host            
     * @param string $port            
     * @param string $schema            
     */
    public function __construct($dbName, $user, $passwd, $host = '127.0.0.1', $port = '3306')
    {
        $this->dbName = $dbName;
        $this->user = $user;
        $this->passwd = $passwd;
        
        if (is_null($host))
            $host = '127.0.0.1';
        $this->host = $host;
        
        if (is_null($port))
            $port = '3306';
        
        $this->port = $port;
    } // end public function __construct */
    
    /**
     * öffnen der datenbankverbindung
     */
    public function open()
    {
        $pgsql_con_string = 'host='.$this->host.' port='.$this->port.' dbname='.$this->dbName.' user='.$this->user.' password='.$this->passwd;
        
        $this->connection = new mysqli($this->host, $this->user, $this->passwd, $this->dbName, $this->port);
        
        if ($this->connection->connect_error) {
            throw new DbException($this->connection->connect_errno.' '.$this->connection->connect_error);
        }
        
    } // end public function open */
    
    /**
     * schliesen der datenbank verbindung
     */
    public function close()
    {
        if ($this->connection)
            $this->connection->close();
    } // end public function close */

  
// //////////////////////////////////////////////////////////////////////////////
// Query Logic
// //////////////////////////////////////////////////////////////////////////////
    
    /**
     * de:
     * eine einfach select abfrage an die datenbank
     * select wird immer auf der lesende connection ausgeführt
     *
     * @param string $sql
     *            ein SQL String
     * @param string $singleRow            
     * @param boolean $expectResult            
     *
     * @return array/scalar
     * @throws DbException - bei inkompatiblen parametern
     */
    public function get($table, $id, $className = null)
    {
        if (! is_resource($this->connection)) {
            $this->open();
        }
        
        $sql = <<<SQL
SELECT * FROM {$table} where rowid = {$id};
SQL;
        
        $result = $this->connection->query($sql);
        if (! $result) {
            throw new DbException($this->connection->error);
        }
        
        $data = $result->fetch_assoc();
        
        if (!$className)
            $className = UtilStrings::subToCamelCase($table).'_Entity';
        else
            $className = $className.'_Entity';
        
        return new $className($data);
        
    } // end public function get */
    
    /**
     * de:
     * eine einfach select abfrage an die datenbank
     * select wird immer auf der lesende connection ausgeführt
     *
     * @param string $sql
     *            ein SQL String
     * @param string $singleRow            
     * @param boolean $expectResult            
     *
     * @return array/scalar
     * @throws DbException - bei inkompatiblen parametern
     */
    public function getWhere($table, $where, $className = null)
    {
        if (! is_resource($this->connection)) {
            $this->open();
        }
        
        $sql = <<<SQL
  SELECT * FROM {$table} where {$where};
SQL;
        
        $result = $this->connection->query($sql);
        
        if (! $result) {
            if ($this->connection)
                throw new DbException($this->connection->error.' '.$sql);
            else
                throw new DbException('Connection died '.$sql);
        }
        
        $data = $result->fetch_assoc();
        
        if (! $className)
            $className = UtilStrings::subToCamelCase($table).'_Entity';
        else
            $className = $className.'_Entity';
        
        return new $className($data);
    } // end public function get */
    
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
     * @throws DbException - bei inkompatiblen parametern
     */
    public function select($sql, $expectResult = false)
    {
        if (! is_resource($this->connection)) {
            $this->open();
        }
        
        $result = $this->connection->query($sql);
        
        if (!$result) {
            throw new DbException($this->connection->error);
        }
        
        return new DbMysqlResult($result, $this);
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
    public function insert($sql)
    {
        if (! is_resource($this->connection)) {
            $this->open();
        }
        
        if (is_string($sql)) {
            
            $this->connection->query($sql);
            return $this->connection->insert_id;
            
        } else {
            
            $keys = $sql->getCols();
            $saveFields = array();
            
            foreach ($keys as $field) {
                $saveFields[$field] = $sql->escaped($field, $this);
            }
            
            $sqlField = '('.implode(', ', array_keys($saveFields)).')';
            $sqlValues = 'VALUES('.implode(', ', $saveFields).')';
            
            $sqlString = <<<SQL
INSERT INTO {$sql->getTable()} {$sqlField} {$sqlValues};
SQL;
            
            $this->connection->query($sqlString);
            
            if ($this->connection->error) {
                throw new DbException($this->connection->error);
            }
            
            if ($sql->pkSequence()) {
                $sql->rowid = $this->connection->insert_id;
            }
            
            return $sql->rowid;
        }
        
    }//end public function insert */
    
    /**
     * Ein Updatestatement an die Datenbank schicken
     *
     * @param string $sql
     *            Ein Aktion Object
     * @throws DbException
     * @return int
     */
    public function update($sql)
    {
        if (! is_resource($this->connection)) {
            $this->open();
        }
        
        if (is_string($sql)) {
            
            $this->connection->query($sql);
            return $this->connection->affected_rows;
        } else {
            
            $keys = $sql->getCols();
            $saveFields = array();
            
            foreach ($keys as $field) {
                $saveFields[$field] = $field.' = '.$sql->escaped($field, $this);
            }
            
            $sqlValues = implode(', ', $saveFields);
            
            $sqlString = <<<SQL
UPDATE {$sql->getTable()} set {$sqlValues} where rowid = {$sql->rowid};
SQL;
            
            $this->connection->query($sqlString);
            
            if ($this->connection->error) {
                throw new DbException($this->connection->error.' '.$sqlString);
            }
            
            return $this->connection->affected_rows;
        }
        
    }//end public function update */
    
    /**
     * Ein Delete Statement
     *
     * @param string $sql
     *            Ein Aktion Object
     * @throws DbException
     * @return int
     */
    public function delete($sql, $id = null)
    {
        if (! is_resource($this->connection)) {
            $this->open();
        }
        
        // id
        if ($id) {
            $sql = <<<SQL
DELETE FROM {$sql} where rowid = {$id};
SQL;
        }
        
        if (is_string($sql)) {
            
            $this->connection->query($sql);
            return $this->connection->affected_rows;
        } else {
            
            $sqlString = <<<SQL
DELETE FROM {$sql->getTable()} where {$sql->rowid};
SQL;
            
            $this->connection->query($sqlString);
            
            if ($this->connection->error) {
                throw new DbException($this->connection->error);
            }
            
            return $this->connection->affected_rows;
        }
    } // end public function delete */
      
// //////////////////////////////////////////////////////////////////////////////
// Escaping
// //////////////////////////////////////////////////////////////////////////////
    
    /**
     * Escapen eines strings
     * 
     * @param string $string            
     * @param string $type            
     *
     * @return string der escapte string
     */
    public function escape($string, $type = 'text')
    {
        if (! is_resource($this->connection)) {
            $this->open();
        }
        
        if (isset($this->escapeTypes[$type]))
            return "'".$this->connection->real_escape_string($string)."'";
        else
            return $string;
    } // end public function escape */
      
// //////////////////////////////////////////////////////////////////////////////
// Transaction Code
// //////////////////////////////////////////////////////////////////////////////
    
    /**
     * Starten einer Transaktion
     *
     * @throws DbException
     */
    public function begin()
    {
        if (! is_resource($this->connection)) {
            $this->open();
        }
        
        $this->connection->begin();
    } // end public function begin */
    
    /**
     * Transaktion wegen Fehler abbrechen
     *
     * @throws DbException
     */
    public function rollback()
    {
        $this->connection->rollback();
    } // end public function rollback */
    
    /**
     * Transaktion erfolgreich Abschliesen
     *
     * @throws DbException
     */
    public function commit()
    {
        $this->connection->commit();
    } // end public function commit */
    
}//end class DbMysql
