<?php

/**
 * class LibImageThumbAdapter
 * @package com.BuizCore
 * @subpackage SimFi
 */
abstract class UtilImageFormatterAdapter
{
/*//////////////////////////////////////////////////////////////////////////////
// Attributes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @var array
   */
  public $origName = null;

  /**
   * Enter description here...
   *
   * @var int
   */
  public $thumbName = null;

  /**
   * Enter description here...
   *
   * @var int
   */
  public $maxHeight = 100;

  /**
   * Enter description here...
   *
   * @var int
   */
  public $maxWidth = 100;

/*//////////////////////////////////////////////////////////////////////////////
// Magic
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * Enter description here...
   *
   */
  public function __construct($origName = null, $thumbName = null, $maxWidth = null, $maxHeight = null)
  {

    if ($origName) {
      $this->origName = $origName;
    }

    if ($thumbName) {
      $this->thumbName = $thumbName;
    }

    if ($maxWidth) {
      $this->maxWidth = $maxWidth;
    }

    if ($maxHeight) {
      $this->maxHeight = $maxHeight;
    }

  }//end public function __construct

/*//////////////////////////////////////////////////////////////////////////////
// Getter and Setter
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * Enter description here...
   *
   */
  public function setOrigname($origName)
  {
    $this->origName = $origName;

  }//end public function setOrigname

    /**
   * Enter description here...
   *
   */
  public function setThumbName($thumbName)
  {
    $this->thumbName = $thumbName;

  }//end public function setThumbName

  /**
   * Enter description here...
   *
   */
  public function setMaxHeight($maxHeight)
  {
    $this->maxHeight = $maxHeight;

  }//end public function setMaxHeight

  /**
   * Enter description here...
   *
   */
  public function setMaxWidth($maxWidth)
  {
    $this->maxWidth = $maxWidth;

  }//end public function setMaxWidth

}// end abstract class LibImageThumbAdapter

