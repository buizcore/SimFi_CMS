<?php



/**
 * 
 * @subpackage web_expert.cms
 */
class Login_Controller extends MvcController
{

  /**
   * @service
   */
  public function do_default()
  {

    $request = $this->getRequest();
    $console = $this->getConsole();

    $workarea = $console->tpl->getWorkArea( );
    $workarea->addTemplate( 'login/login' );

  }//end public function do_default */

  /**
   * @service
   */
  public function do_login()
  {

    $request = $this->getRequest();
    $console = $this->getConsole();

    $workarea = $console->tpl->getWorkArea( );
    $workarea->addTemplate( 'login/success' );

  }//end public function do_default */

}//end class Welcome_Controller */
