<?php



/**
 * @package com.BuizCore
 * @subpackage SimFi
 */
class TemplateWorkarea
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var string
   */
  public $title = array();


  /**
   * Wenn raw, dann hat das index template einen komplett eigenes html gedöhns
   * @var boolean
   */
  public $rawTemplate = false;


  /**
   * @var string
   */
  public $caption = null;

  /**
   * @var TArray
   */
  public $vars = null;

  /**
   * @var array
   */
  public $templates = array();

  /**
   * @var string
   */
  public $index = 'default';

  /**
   * @var string
   */
  public $menu = null;

  /**
   * @var string
   */
  public $activePage = null;

  /**
   * @var string
   */
  public $rqtPage = null;

  /**
   * @var string
   */
  public $lang = null;

  /**
   * @var string
   */
  public $renderedContent = null;

  /**
   * @var string
   */
  public $contentType = 'text/html';

  /**
   * @var string
   */
  public $encoding = 'utf-8';

  /**
   * The Configuration Object
   * @var Conf
   */
  public $conf = null;

////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   */
  public function __construct()
  {

    $this->vars = new TArray();
    $this->conf = Conf::getActive();

  }//end public function __construct */

  /**
   * Sender der Header der Workarea
   */
  public function sendHeader()
  {

    header( 'Content-Type:'.$this->contentType.'; charset='.$this->encoding, true );
    header( 'ETag: '.md5($this->renderedContent), true );
    header( 'Content-Length: '.mb_strlen($this->renderedContent), true );

  }//end public function sendHeader */

  /**
   * @param string $caption
   */
  public function setCaption( $caption )
  {

    $this->caption = $caption;

  }//end public function setCaption */

  /**
   * @param string $template
   */
  public function addTemplate( $template )
  {

    $this->templates[] = $template;

  }//end public function addTemplate */


  /**
   * @param string $template
   */
  public function addVar( $key, $var )
  {

    $this->vars->set($key, $var);

  }//end public function addTemplate */

  /**
   * @param string $template
   */
  public function addVars( $vars )
  {

    foreach($vars as $key => $var){
      $this->vars->set($key, $var);
    }


  }//end public function addTemplate */

  public function title()
  {

    if (isset($this->title[$this->lang])) {
      return $this->title[$this->lang];
    } else {
      $this->conf->title;
    }

  }//end public function title */

  /**
   * @return string
   */
  public function render()
  {

    $v = $this->vars;
    $imagePath = $this->conf->img_path.'/themes/'.$this->conf->theme.'/images/';

    if(!$this->lang)
      $this->lang = $this->conf->lang;

    ob_start();
    foreach( $this->templates as $template ) {

      $i18nTpl = ($this->lang?$this->lang.'/':'').$template.'.tpl';

      //echo  PATH_ROOT.$this->conf->page_root.'/content/pages/'.$i18nTpl;

      if( Fs::exists( PATH_ROOT.$this->conf->page_root.'/content/pages/'.$i18nTpl ) )
        include PATH_ROOT.$this->conf->page_root.'/content/pages/'.$i18nTpl;
      elseif( Fs::exists( PATH_ROOT.$this->conf->page_root.'/module/'.$i18nTpl ) )
        include PATH_ROOT.$this->conf->page_root.'/module/'.$i18nTpl;
      elseif( Fs::exists( PATH_ROOT.$this->conf->page_root.'/src/'.$i18nTpl ) )
        include PATH_ROOT.$this->conf->page_root.'/src/'.$i18nTpl;
      elseif( Fs::exists( WEBX_PATH.'modules/'.$i18nTpl ) )
        include WEBX_PATH.'modules/'.$i18nTpl;
      elseif( Fs::exists( WEBX_PATH.'src/'.$i18nTpl ) )
        include WEBX_PATH.'src/'.$i18nTpl;
      else{
        $i18nErrorTpl = ($this->lang?$this->lang.'/':'').'error/404.tpl';
        include PATH_ROOT.$this->conf->page_root.'/content/pages/'.$i18nErrorTpl;
      }
    }

    $maincontent = ob_get_contents();
    ob_end_clean();


    $menu = null;
    if ($this->menu) {
      ob_start();

      $menuTpl = ($this->lang?$this->lang.'/':'').$this->menu.'.tpl';
      if (Fs::exists(PATH_ROOT.$this->conf->page_root.'/content/menus/'.$menuTpl)) {
        include PATH_ROOT.$this->conf->page_root.'/content/menus/'.$menuTpl;
      }

      $menu = ob_get_contents();
      ob_end_clean();
    }

    ob_start();

    $i18nLayout = ($this->lang?$this->lang.'/':'').$this->index.'.tpl';
    if( Fs::exists( PATH_ROOT.$this->conf->page_root.'/content/layouts/'.$i18nLayout ) )
      include PATH_ROOT.$this->conf->page_root.'/content/layouts/'.$i18nLayout;
    else if( Fs::exists( WEBX_PATH.'content/layouts/'.$i18nLayout ) )
      include WEBX_PATH.'content/layouts/'.$i18nLayout;
    else
      echo 'Missing Index '.PATH_ROOT.$this->conf->page_root.'/content/layouts/'.$i18nLayout.NL;
    $redered = ob_get_contents();
    ob_end_clean();


    if( $this->rawTemplate ){
      $this->renderedContent = $redered;
      return $this->renderedContent;
    }

    $this->renderedContent = <<<HTML
<html>
  <head>
    <title>{$this->title()}</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="content-script-type" content="application/javascript" />
    <meta http-equiv="content-style-type" content="text/css" />
    <link type="text/css" href="layout.php" rel="stylesheet" />
    <link type="text/css" href="theme.php"  rel="stylesheet" />
  </head>
  <body>
    {$redered}
    <script type="application/javascript" src="javascript.php" ></script>
  </body>
</html>

HTML;

    return $this->renderedContent;

  }//end public function render */

}//end class TemplateWorkarea */