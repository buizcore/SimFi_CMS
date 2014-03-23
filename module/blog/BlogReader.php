<?php


/**
 * @package com.BuizCore
 * @subpackage SimFi
 */
class BlogReader
{

  public $db = null;

  /**
   */
  public function __construct()
  {
    $this->db = Db::getConnection('default');
  }//end public function __construct */

  /**
   * @param int $offset
   * @param int $limit
   * @param boolean $showInactive
   * @return array
   */
  public function getEntries($offset = null, $limit = 5, $showInactive = false)
  {

    $where = '';

    if (!$showInactive) {
      $where .= ' WHERE active = 1 ';
    }

    // laden der Einträge
    $sql = <<<SQL
SELECT *
FROM simfi_blog_entry
{$where}
ORDER BY created desc

SQL;

    if($limit)
      $sql .= " limit {$limit} ";

    if($offset)
      $sql .= " offset {$offset} ";

    $sql .= ';';

    return $this->db->select($sql)->getAll();

  }//end public function getEntries */



}//end class BlogReader */
