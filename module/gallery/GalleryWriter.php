<?php


/**
 * @package com.BuizCore
 * @subpackage SimFi
 */
class GalleryWriter
{

  /**
   * @var DbMysql
   */
  public $db = null;

  /**
   * @var Conf
   */
  public $conf = null;


  /**
   * 
   */
  public function __construct()
  {
    $this->db = Db::getConnection('default');
    $this->conf = Conf::getActive();

  }//end public function __construct */

  /**
   * Save the galler request
   */
  public function saveRequest($entryId)
  {


    if($entryId){
      $entryNode = $this->db->get( 'simfi_gallery_entry', $entryId, 'GalleryEntry');
    } else {
      $entryNode = new GalleryEntry_Entity();
      $entryNode->created = date('Y-m-d H:i:s');
      $entryNode->author = 'bc';
      $entryNode->active = 0;
    }

    $entryNode->title = $_POST['gallery']['title'];
    $entryNode->content = $_POST['gallery']['content'];
    $entryNode->img_name = isset($_POST['gallery']['img_name'])?$_POST['gallery']['img_name']:null;
    $entryNode->img_name_big = isset($_POST['gallery']['img_name_big'])?$_POST['gallery']['img_name_big']:null;
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
    $savePath = PATH_ROOT.$this->conf->page_root.'/static/images/gallery/';

    $data = array();
    $data['name'] = $_FILES['image']['name'];
    $data['type'] = $_FILES['image']['type'];
    $data['tmp_name'] = $_FILES['image']['tmp_name'];
    $data['error'] = $_FILES['image']['error'];
    $data['size'] = $_FILES['image']['size'];


    $imgRes = new UtilImageFormatter_Gd();
    $imgRes->resize($data['tmp_name'], $savePath.$data['name'], 440, 360);
    $imgRes->resize($data['tmp_name'], $savePath.'big/'.$data['name'], 800, 600);


    if ($entryId) {
      $entryNode = $this->db->get( 'simfi_gallery_entry', $entryId, 'GalleryEntry');

      $entryNode->img_name = 'static/images/gallery/'.$data['name'];
      $entryNode->img_name_big = 'static/images/gallery/big/'.$data['name'];
      $this->db->update($entryNode);
    }

    return array(
      'static/images/gallery/'.$data['name'],
      'static/images/gallery/big/'.$data['name']
    );

  }//end public function saveSaveImage */

  /**
   * @param string $entryId
   */
  public function deleteEntry($entryId)
  {

    $this->db->delete( 'simfi_gallery_entry', $entryId);

  }//end public function deleteEntry */

  /**
   * @param boolean $publish
   */
  public function publish($publish)
  {

    $entryId = (int)$_GET['entry'];

    $entryNode = $this->db->get( 'simfi_gallery_entry', $entryId, 'GalleryEntry');

    if ($publish) {
      $entryNode->active = 1;
    } else {
      $entryNode->active = 0;
    }

    $this->db->update($entryNode);

    return $entryNode;

  }//end public function publish */



}//end class BlogWriter */
