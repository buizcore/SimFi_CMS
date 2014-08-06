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
 * Basisklasse für die Datenbankverbindung
 * @package com.BuizCore
 * @subpackage SimFi
 */
class Db_Result implements Iterator
{



    protected $result = null;
    
    protected $connection = null;
    
    protected $row = null;
    
    protected $pos = 0;



    /**
     * @param Resouce $result
     * @param Resouce $connection
     */
    public function __construct($result, $connection)
    {
        $this->result = $result;
        $this->connection = $connection;
    }//end public function __construct */
    
    /**
     *
     */
    public function getAll()
    {
        return array();
    }//end public function getAll */
    
    /**
     *
     */
    public function get()
    {
        return array();
    }//end public function getAll */

    
/*//////////////////////////////////////////////////////////////////////////////
 // Interface: Iterator
 //////////////////////////////////////////////////////////////////////////////*/

    /**
     *
     * @return array
     */
    public function current()
    {
        return $this->row;
    }//end public function current ()
    
    /**
     *
     * @return int
     */
    public function key()
    {
        return $this->pos;
    }//end public function key ()
    
    /**
     *
     * Enter description here...
     * @return array
     */
    public function next()
    {
        return $this->get();
    }//end public function next ()
    
    /**
     */
    public function rewind ()
    {
        $this->row = array();
        $this->pos = 0;
        $this->rewind();
    }//end public function rewind ()
    
    /**
     * (non-PHPdoc)
     * @see src/lib/db/LibDbResult#valid()
     */
    public function valid ()
    {
        if (0 === $this->pos)
            $this->get();
    
        return !is_null($this->pos);
    }//end public function valid ()
    
}