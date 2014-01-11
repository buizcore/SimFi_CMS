<?php



/**
 * @package WebFrap
 * @subpackage WebExpert
 */
class TemplateFile
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var TArray
   */
  public $vars = null;

  /**
   * @var array
   */
  public $templates = array();


////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   */
  public function __construct()
  {

    $this->vars = new TArray();

  }//end public function __construct */


  /**
   * @param string $template
   */
  public function addTemplate( $template )
  {

    $this->templates[] = $template;

  }//end public function addTemplate */

  /**
   * @return string
   */
  public function render()
  {

    $v = $this->vars;

    ob_start();
    foreach( $this->templates as $template ) {
      if( Fs::exists( WEBX_PATH.'module/'.$template.'.tpl' ) )
        include WEBX_PATH.'module/'.$template.'.tpl';
      elseif( Fs::exists( WEBX_PATH.'src/'.$template.'.tpl' ) )
        include WEBX_PATH.'src/'.$template.'.tpl';
      else
        echo 'Missing Template '.$template.NL;
    }

    $maincontent = ob_get_contents();
    ob_end_clean();

    $this->renderedContent = $maincontent;

    return $this->renderedContent;

  }//end public function render */

  public function save( $filename )
  {

  }//end public function save */

}//end class TemplateFile */