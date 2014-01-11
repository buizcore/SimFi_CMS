<?php



/**
 * @package WebFrap
 * @subpackage WebExpert
 */
class WgtForm extends WgtElement
{

  /**
   * @var string
   */
  public $method = null;

  /**
   * @var string
   */
  public $id  = null;

  /**
   * @param [WgtInput]
   */
  public $inputs = array();


  public static function checked($val)
  {

    if($val){
      echo ' checked="checked" ';
    }

  }

  public static function selected($val, $contentVal)
  {

    if($val == $contentVal){
      echo ' selected="selected" ';
    }

  }

}//end class WgtForm */
