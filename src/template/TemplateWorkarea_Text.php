<?php



/**
 * @package com.BuizCore
 * @subpackage SimFi
 */
class TemplateWorkarea_Text extends TemplateWorkarea
{

  /**
   * @var string
   */
  public $contentType = 'text/plain';

  /**
   * @var string
   */
  public $index = 'template/plain';

  /**
   * @return string
   */
  public function render()
  {

    $v = $this->vars;

    ob_start();
    foreach( $this->templates as $template ) {

      if( Fs::exists( PATH_ROOT.$this->conf->page_root.'/content/static/'.$template.'.tpl' ) )
        include PATH_ROOT.$this->conf->page_root.'/content/static/'.$template.'.tpl';
      elseif( Fs::exists( PATH_ROOT.$this->conf->page_root.'/module/'.$template.'.tpl' ) )
        include PATH_ROOT.$this->conf->page_root.'/module/'.$template.'.tpl';
      elseif( Fs::exists( PATH_ROOT.$this->conf->page_root.'/src/'.$template.'.tpl' ) )
        include PATH_ROOT.$this->conf->page_root.'/src/'.$template.'.tpl';
      elseif( Fs::exists( SIMFI_CODE_PATH.'module/'.$template.'.tpl' ) )
        include SIMFI_CODE_PATH.'modules/'.$template.'.tpl';
      elseif( Fs::exists( SIMFI_CODE_PATH.'src/'.$template.'.tpl' ) )
        include SIMFI_CODE_PATH.'src/'.$template.'.tpl';
      else
        echo 'Missing Template '.$template.NL;
    }

    $maincontent = ob_get_contents();
    ob_end_clean();

    ob_start();

    if( Fs::exists( PATH_ROOT.$this->conf->page_root.'/content/layouts/'.$this->index.'.tpl' ) )
      include PATH_ROOT.$this->conf->page_root.'/content/layouts/'.$this->index.'.tpl';
    else if( Fs::exists( SIMFI_CODE_PATH.'content/layouts/'.$this->index.'.tpl' ) )
      include SIMFI_CODE_PATH.'content/layouts/'.$this->index.'.tpl';
    else
      echo 'Missing Index '.PATH_ROOT.$this->conf->page_root.'/content/layouts/'.$this->index.'.tpl'.NL;
    $redered = ob_get_contents();
    ob_end_clean();

    $this->renderedContent = $redered;

    return $this->renderedContent;

  }//end public function render */

}//end class TemplateWorkarea_Css */