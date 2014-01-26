<?php


/**
 * @package com.BuizCore
 * @subpackage SimFi
 */
class CmsDataWriter
{


  /**
   * @var string
   */
  public $activePage = null;

  /**
   * @var string
   */
  public $cmsTemplate = null;

  /**
   * @var string
   */
  public $title = array();

  /**
   * @var array
   */
  public $texts = array();

  /**
   * @var array
   */
  public $metaTags = array();

  /**
   * @var array
  */
  public $metaDescription = array();

  /**
   * @var array
  */
  public $images = array();

  /**
   *
   * @param TemplateWorkarea_Css $view
   */
  public function __construct($view)
  {

    $this->activePage = $view->activePage;
    $this->cmsTemplate = $view->cmsTemplate;
    $this->activePage = $view->activePage;
    $this->texts = $view->texts;
    $this->images = $view->images;
    $this->metaDescription = $view->metaDescription;
    $this->metaTags = $view->metaTags;
    $this->title = $view->title;

  }


  /**
   * @param $filePath
   */
  public function write( $filePath )
  {


    $file = <<<FILE
<?php
        
// texts

FILE;

    if($this->cmsTemplate){
      $file .= <<<FILE
\$this->cmsTemplate = '{$this->cmsTemplate}';

FILE;
    }


    foreach ($this->title as $lang => $text) {

          $file .= <<<FILE
\$this->title['{$lang}'] = <<<TEXT
{$text}
TEXT;

FILE;

    }// end foreach title

    foreach ($this->metaDescription as $lang => $text) {

      $file .= <<<FILE
\$this->metaDescription['{$lang}'] = <<<TEXT
{$text}
TEXT;

FILE;

    }// end foreach metaDescription

    foreach ($this->metaTags as $lang => $tags) {

      $tagsCode = "array('".implode("', '",$tags )."')";

      $file .= <<<FILE
\$this->metaTags['{$lang}'] = {$tagsCode};

FILE;

    }// end foreach metaTags

    foreach ($this->texts as $lang => $texts) {

      foreach ($texts as $textKey => $textNode) {

        if (is_array($textNode)) {

          foreach ($textNode as $subKey => $subText) {
            $file .= <<<FILE
\$this->texts['{$lang}']['{$textKey}']['{$subKey}'] = <<<TEXT
{$subText}
TEXT;


FILE;
          }

        } else {

          $file .= <<<FILE
\$this->texts['{$lang}']['{$textKey}'] = <<<TEXT
{$textNode}
TEXT;


FILE;
        }
      }

    }// end foreach texts

    $file .= <<<FILE

// images

FILE;

    foreach ($this->images as $imgSrc => $images) {

      if (is_array($images)) {

        if(is_array(current($images))){

          foreach( $images as $imgKey => $imageData ){

            $imgParams = '';

            foreach ($imageData as $imgPKey => $imgParam) {

              if (is_string($imgParam)) {
                $imgParams .= "'{$imgParam}',";
              } else if (is_bool($imgParam) ) {
                $imgParams .= ($imgParam?'true':'false').',';
              } else if( is_array($imgParam)) {
                $imgParams .= 'array( ';
                foreach($imgParam as $lKey => $lText){
                  $imgParams .= "'{$lKey}' => '{$lText}',";
                }
                $imgParams .= ' ), ';

              }  else {

                $imgParams .= "'{$imgParam}',";
              }

            }

            $file .= <<<FILE
\$this->images['{$imgSrc}']['{$imgKey}'] = array({$imgParams});


FILE;
          }

        } else {

          $imgParams = '';

          foreach ($images as $imgPKey => $imgParam) {

            if (is_string($imgParam)) {
              $imgParams .= "'{$imgParam}',";
            } else if (is_bool($imgParam) ) {
              $imgParams .= ($imgParam?'true':'false').',';
            } else {
              $imgParams .= "'{$imgParam}',";
            }

          }

          $file .= <<<FILE
\$this->images['{$imgSrc}'] = array({$imgParams});


FILE;
        }


      } else {

          $file .= <<<FILE
\$this->images['{$imgSrc}'] = '{$images}';


FILE;
      }

    }// end foreach texts



    if (!file_put_contents($filePath, $file)) {
      return 'Es ist ein Fehler beim Schreiben aufgetreten';
    }

    return 'ok';

  }//end public function write */
  

}//end class Console */
