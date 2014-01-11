<?php



/**
 * @package WebFrap
 * @subpackage WebExpert
 */
class Page_Controller extends MvcController
{

  /**
   *
   */
  public function do_default() {

    $request = $this->getRequest();
    $console = $this->getConsole();
    $conf = Conf::getActive();

    $page = $request->param( 'page', Validator::FOLDERNAME );
    $lang = $request->param( 'l', Validator::CNAME  );

    if (!$lang) {
      $lang = 'de';
    }

    if (!$page) {
      if ($conf->start_page)
        $page = $conf->start_page;
      else
        $page = 'start';
    }

    if (isset($conf->pageRoutes[$page])) {
      $page = $conf->pageRoutes[$page];
    }

    if ($conf->preview_sec) {
      if (isset($_POST['prev_user'])) {
        if (isset($conf->preview_sec[$_POST['prev_user']]) && $conf->preview_sec[$_POST['prev_user']] == $_POST['prev_pwd']) {
          $_SESSION['preview_granted'] = true;
        }
      }
    }


    if( $conf->preview_sec && !isset($_SESSION['preview_granted'])){
      $page = 'preview_login';
    }

    $pageTpl = str_replace( array('/','\\','.'), array('','','/'), $page);

    // no more old style
    $workarea = $console->tpl->getWorkArea( 'Cms' );
    $workarea->rqtPage = $page;

    $workarea->lang = $lang;
    $workarea->addTemplate( $pageTpl );

  }//end public function do_default */

}//end class Welcome_Controller */
