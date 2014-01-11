<?php


/**
 * 
 * @subpackage web_expert.cms
 */
class BlogWriter
{

  /**
   * @var DbMysql
   */
  public $db = null;

  /**
   * @var Conf
   */
  public $conf = null;


  public function __construct()
  {
    $this->db = Db::getConnection('default');
    $this->conf = Conf::getActive();

  }

  public function saveRequest($entryId)
  {


    if($entryId){
      $entryNode = $this->db->get( 'bc_blog_entry', $entryId, 'BlogEntry');
    } else {
      $entryNode = new BlogEntry_Entity();
      $entryNode->template =  $_GET['tpl'];
      $entryNode->created = date('Y-m-d H:i:s');
      $entryNode->author = 'bc';
      $entryNode->active = 0;
    }

    $entryNode->titel = $_POST['blog']['titel'];
    $entryNode->content = $_POST['blog']['content'];
    $entryNode->images = isset($_POST['blog']['images'])?$_POST['blog']['images']:null;
    $entryNode->last_update = date('Y-m-d H:i:s');

    if($entryId){
      $this->db->update($entryNode);
      return $entryNode;
    } else {
      $this->db->insert($entryNode);
      return $entryNode;
    }

  }//end public function saveRequest */

  /**
   * @param string $savePath
   */
  public function saveSaveImage()
  {

    $entryId = (int)$_GET['entry'];

    $savePath = PATH_ROOT.$this->conf->page_root.'/static/images/blog/';

    $data = array();
    $data['name']     = $_FILES['image']['name'];
    $data['type']     = $_FILES['image']['type'];
    $data['tmp_name'] = $_FILES['image']['tmp_name'];
    $data['error']    = $_FILES['image']['error'];
    $data['size']     = $_FILES['image']['size'];


    $imgRes = new UtilImageFormatter_Gd();
    $imgRes->resize($data['tmp_name'], $savePath.$data['name'], 350, 300);
    $imgRes->resize($data['tmp_name'], $savePath.'big/'.$data['name'], 800, 600);


    if ($entryId) {
      $entryNode = $this->db->get( 'bc_blog_entry', $entryId, 'BlogEntry');

      $entryNode->images = 'static/images/blog/'.$data['name'];
      $entryNode->images_big = 'static/images/blog/big/'.$data['name'];
      $this->db->update($entryNode);
    }

    return 'static/images/blog/'.$data['name'];

  }//end public function saveSaveImage */

  /**
   * @param string $entryId
   */
  public function deleteEntry($entryId)
  {

    $this->db->delete( 'bc_blog_entry', $entryId);

  }//end public function deleteEntry */

  /**
   * @param boolean $publish
   */
  public function publish($publish)
  {

    $entryId = (int)$_GET['entry'];

    $entryNode = $this->db->get( 'bc_blog_entry', $entryId, 'BlogEntry');

    if ($publish) {
      $entryNode->active = 1;
    } else {
      $entryNode->active = 0;
    }

    $this->db->update($entryNode);

    return $entryNode;

  }//end public function publish */



}//end class BlogWriter */
