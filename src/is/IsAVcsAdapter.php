<?php


  
/**
 * Interface für den zugriff auf Code Repositories
 * 
 * @package WebFrap
 * @subpackage WebExpert
 */
interface IsAVcsAdapter
{
////////////////////////////////////////////////////////////////////////////////
// Usage Logic
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * @return string
   */
  public function getVersion();
  
  /**
   * @return string
   */
  public function getType();
  
  /**
   * Prüfen ob das Repository überhaupt schon initialisiert ist
   * 
   * @return boolean
   */
  public function isRepository();
  
////////////////////////////////////////////////////////////////////////////////
// Usage Logic
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * Commiten von Änderungen
   * @param string $message
   */
  public function commit( $message );
  
  /**
   * @param boolean $justCheckChanges nur prüfen ob es Änderungen gab
   * @return string|boolean
   */
  public function status( $justCheckChanges = false );
  
  /**
   * @param string $branch
   */
  public function update( $branch = null );
  
////////////////////////////////////////////////////////////////////////////////
// Umgang mit Branches
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * Den Branch anfrage in welchem sich das Repostory im Moment befindet
   * @return string
   */
  public function getActualBranch();
  
  /**
   * Eine Liste der Branches erfragen, die aktuell vorhanden sind
   * @return array
   */
  public function getBranches();
  
  /**
   * In einen Branch wechseln
   * 
   * Es wwird davon ausgegangen, dass der Branch existiert
   * Wenn das nicht klar ist muss die Existenz vorab mit hasBranch geprüft werden
   * 
   * @param string $branch
   * @return string
   * 
   * @throws WebExpertException Wenn der Branch nicht existitert oder bei sonstigen fehlern
   */
  public function switchBranch( $branch );
  
  /**
   * Checken ob ein bestimmter Branch überhaupt existiert
   * @param string $branch
   * @return boolean
   */
  public function hasBranch( $branch );
  
////////////////////////////////////////////////////////////////////////////////
// Merge Logik
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * @param string $target
   * @param string $source
   * @param string $commitMessage
   */
  public function mergeBranches( $target, $source, $commitMessage = null );
  
////////////////////////////////////////////////////////////////////////////////
// Head
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $branch
   * @return string
   */
  public function getHead( $branch );

  /**
   * @param string $branch
   * @return array
   */
  public function getHeads( );
  
  
}//end class IsAVcsAdapter */

