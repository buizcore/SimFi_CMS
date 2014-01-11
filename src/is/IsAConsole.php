<?php


  
/**
 * Interface für Datenbank Verbindungen
 * @package com.BuizCore
 * @subpackage SimFi
 */
interface IsAConsole
{

  /**
   * @return array
   */
  public function version( );
  
  /**
   * @param string $text
   * @return void
   */
  public function out( $text );
  
  /**
   * @return string
   */
  public function in( );
  
  /**
   * @param string $info
   */
  public function debug( $info );
  
  /**
   * @param string $info
   */
  public function info( $info );
  
  /**
   * @param string $warning
   */
  public function warning( $warning );
  
  /**
   * @param string $warning
   */
  public function error( $error );


  /**
   * @param string $question
   * @return boolean
   */
  public function question( $question );
  
  /**
   * @param string $text
   * @param string $icon
   */
  public function notification( $text, $icon = "info" );
  
  /**
   * @param string $fileName
   */
  public function fileSelector( $fileName );
  
  
  /**
   * @param string $folderName
   */
  public function folderSelector( $folderName );
  
  /**
   * @param string $text
   * @param string $title
   * @param string $entryText
   */
  public function readText
  ( 
    $text, 
    $title = "Insert Value",
    $entryText  = null,
    $required   = false,
    $validator  = null
  );
  
  /**
   * @param string $text
   * @param string $title
   */
  public function readPassword( $text, $title = "Insert Password" );
  
  /**
   * @param string $text
   * @param string $data
   * @param string $head
   */
  public function dataList
  ( 
    $title, 
    array $data, 
    array $head = array(), 
    UiDimension $dimension = null  
  );
  
  /**
   * @param string $text
   * @param string $data
   * @param string $head
   */
  public function radioList
  ( 
    $title, 
    array $data, 
    array $head = array(), 
    UiDimension $dimension = null 
  );

  /**
   * @param string $info
   * @param string $file
   * @param string $checkboxText
   */
  public function dialog( $title, $file, $checkboxText = null );
  
  
}//end class IsaDbConsole */

