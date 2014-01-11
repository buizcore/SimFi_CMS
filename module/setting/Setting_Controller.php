<?php



/**
 * @package WebFrap
 * @subpackage WebExpert
 */
class Setting_Controller extends MvcController
{

  /**
   *
   */
  public function do_default()
  {


    if (!isset($_SESSION['user'])) {
      return;
    }

    if (!isset($_POST)) {
      return;
    }

    $request = $this->getRequest();
    $console = $this->getConsole();

    $key = $_GET['key'];
    $type = isset($_GET['type'])?$_GET['type']:'s';

    if (!isset($_POST[$key])) {
      return;
    }

    $conf = Conf::getActive();


    $data = array();

    if('f'==$type){
      foreach($_POST[$key] as $vKey => $value) {
        $data[$vKey] = (float)str_replace(',','.',$value);
      }
    } else if('i'==$type){
      foreach($_POST[$key] as $vKey => $value) {
        $data[$vKey] = (int)$value;
      }
    } else {
      $data = $_POST[$key];
    }

    $pageWriter = new SettingDataWriter($data,$key);


    $console->tpl->renderedContent = $pageWriter->write(PATH_ROOT.$conf->page_root.'/content/data/'.$key.'.php');



  }//end public function do_default */

}//end class Setting_Controller */
