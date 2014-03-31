<?php


/**
 * 
 * @subpackage web_expert
 */
class CmsPageDescriptionWriter
{

  /**
   * @var array
  */
  public $pageDescription = array();


  /**
   *
   * @param TemplateWorkarea_Css $view
   */
  public function __construct($view)
  {

    $this->pageDescription = $view->pageDescription;

  }

  /**
   * @param $filePath
   */
  public function write( $filePath )
  {


    $file = <<<FILE
<?php

FILE;


    foreach ($this->pageDescription as $lang => $texts) {
        
        foreach( $texts as $pos => $text ){
            
      $file .= <<<FILE
    \$this->pageDescription['{$lang}'][{$pos}]['subject'] = <<<TEXT
{$text['subject']}
TEXT;
    
    \$this->pageDescription['{$lang}'][{$pos}]['link'] = <<<TEXT
{$text['link']}
TEXT;
    
    \$this->pageDescription['{$lang}'][{$pos}]['content'] = <<<TEXT
{$text['content']}
TEXT;

FILE;

        } // end texts

    }// end foreach pageDescription


    if (!file_put_contents($filePath, $file)) {
      return 'Es ist ein Fehler beim Schreiben aufgetreten';
    }

    return 'ok';

  }//end public function write */


}//end class Console */
