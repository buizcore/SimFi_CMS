<?php



/**
 * Betriebsystem spezifische elemente
 * @package com.BuizCore
 * @subpackage SimFi
 */
class Template_Http
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var string
   */
  public $renderedContent = null;

  /**
   * @var TemplateWorkarea
   */
  public $workarea = null;


////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   */
  public function __construct()
  {

  }//end public function __construct */

  /**
   * @param string $key
   * @throws WebExpertException
   */
  public function useWorkArea( $key )
  {

    $className = 'TemplateWorkarea_'.FormatString::subToCamelCase( $key );

    if( WebExpert::classLoadable( $className ) )
      $this->workarea = new $className( );
    else
      throw new WebExpertException( "Workarea {$key} existiert nicht." );

    return $this->workarea;

  }//end public function useWorkArea */

  /**
   * @return TemplateWorkarea
   */
  public function getWorkArea( $key = null )
  {

    if( !$this->workarea ) {

      if( $key ) {
        $className = 'TemplateWorkarea_'.FormatString::subToCamelCase( $key );

        if( WebExpert::classLoadable( $className ) )
          $this->workarea = new $className( );
        else
          throw new WebExpertException( "Workarea {$key} existiert nicht." );

      } else {

        $this->workarea = new TemplateWorkarea_Default( );
      }

    }

    return $this->workarea;

  }//end public function getWorkArea */

  /**
   * @return string
   */
  public function render()
  {

    if($this->renderedContent){
      return $this->renderedContent;
    }

    if( $this->workarea )
      $this->renderedContent = $this->workarea->render();
    else
      $this->renderedContent = 'Ok';

    return $this->renderedContent;

  }//end public function render */

  /**
   *
   */
  public function sendHeader()
  {

    if($this->workarea)
      $this->workarea->sendHeader();

  }//end public function sendHeader */


}//end class Template_Http */