<?php


/**
 * Reiner Datencontainer zum speichern von Permissions auf Ordnern
 * 
 * @subpackage web_expert
 */
class StructPermission
{

  /**
   * @var string $owner
   */
  public $owner = null;

  /**
   * @var string $group
   */
  public $group = null;

  /**
   * @var string $directory
   */
  public $directory = null;

  /**
   * @var string $accessMask
   */
  public $accessMask = null;

  /**
   * @var string $recursive
   */
  public $recursive = true;


  /**
   * Für einen neuen Pfad clonen
   * @param string $path
   * @return StructPermission
   */
  public function cloneForPath( $path )
  {

    $newPerm = clone $this;
    $newPerm->directory = $path;

    return $newPerm;

  }//end public function cloneForPath */


}//end class StructPermission */
