<?php


/**
 * Den Apache konfigurieren
 *
 * 
 * @subpackage web_expert
 */
class UiDimension
{

  /**
   * @var int
   */
  public $width = null;

  /**
   * @var int
   */
  public $height = null;

  /**
   * @param int $width
   * @param int $height
   */
  public function __construct( $width = null, $height = null )
  {

    $this->width   = $width;
    $this->height  = $height;

  }//end public function __construct */

  /**
   * @return string
   */
  public function render()
  {

    $html = ' ';

    if( $this->width )
      $html .= ' --width='.$this->width;

    if( $this->height )
      $html .= ' --height='.$this->height;

    return $html;

  }//end public function render */

  /**
   * @return string
   */
  public function __toString()
  {
    return $this->render();
  }//end public function __toString */

}//end class UiDimension */

