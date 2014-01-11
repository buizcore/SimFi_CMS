<?php



/**
 * @package WebFrap
 * @subpackage WebExpert
 */
class UserMgmt_Controller extends MvcController
{

  /**
   *
   */
  public function do_default()
  {

    $request = $this->getRequest();
    $console = $this->getConsole();

    $workarea = $console->tpl->getWorkArea( );
    $workarea->addTemplate( 'welcome/welcome' );

  }//end public function do_default */

  /**
   *
   */
  public function do_list()
  {

    $request = $this->getRequest();
    $console = $this->getConsole();

    $workarea = $console->tpl->getWorkArea( );
    $workarea->addTemplate( 'usermgmt/list' );

  }//end public function do_list */

  /**
   *
   */
  public function do_create()
  {

    $request = $this->getRequest();
    $console = $this->getConsole();

    $workarea = $console->tpl->getWorkArea( );
    $workarea->addTemplate( 'usermgmt/create' );

  }//end public function do_create */

  /**
   *
   */
  public function do_insert()
  {

    $request = $this->getRequest();
    $console = $this->getConsole();

    $workarea = $console->tpl->getWorkArea( );
    $workarea->addTemplate( 'usermgmt/create' );

  }//end public function do_insert */

  /**
   *
   */
  public function do_edit()
  {

    $request = $this->getRequest();
    $console = $this->getConsole();

    $workarea = $console->tpl->getWorkArea( );
    $workarea->addTemplate( 'usermgmt/create' );

  }//end public function do_edit */

  /**
   *
   */
  public function do_update()
  {

    $request = $this->getRequest();
    $console = $this->getConsole();

    $workarea = $console->tpl->getWorkArea( );
    $workarea->addTemplate( 'usermgmt/create' );

  }//end public function do_update */

  /**
   *
   */
  public function do_activate()
  {

    $request = $this->getRequest();
    $console = $this->getConsole();

    $workarea = $console->tpl->getWorkArea( );
    $workarea->addTemplate( 'usermgmt/create' );

  }//end public function do_activate */

  /**
   *
   */
  public function do_ban()
  {

    $request = $this->getRequest();
    $console = $this->getConsole();

    $workarea = $console->tpl->getWorkArea( );
    $workarea->addTemplate( 'usermgmt/create' );

  }//end public function do_ban */

  /**
   *
   */
  public function do_unban()
  {

    $request = $this->getRequest();
    $console = $this->getConsole();

    $workarea = $console->tpl->getWorkArea( );
    $workarea->addTemplate( 'usermgmt/create' );

  }//end public function do_unban */

  /**
   *
   */
  public function do_delete()
  {

    $request = $this->getRequest();
    $console = $this->getConsole();

    $workarea = $console->tpl->getWorkArea( );
    $workarea->addTemplate( 'usermgmt/create' );

  }//end public function do_edit */

}//end class Usermgmt_Controller */
