<?php



/**
 * Ausgabe von UI elementen in die shell
 * @package com.BuizCore
 * @subpackage SimFi
 */
class UiConsoleHttp extends UiConsoleCli implements IsAConsole
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var string
   */
  public $type = 'http';

  /**
   * @var Template_Http
   */
  public $tpl = null;

  /**
   * @var array
   */
  private $version = array();

  /**
   * @var UiConsoleCli
   */
  private static $default = null;

////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return UiConsoleHttp
   */
  public static function getActive()
  {

    if( !self::$default )
      self::$default = new UiConsoleHttp();

    return self::$default;

  }//end public static function getActive */

  /**
   *
   */
  public function __construct()
  {
    $this->tpl = new Template_Http();
  }//end public function __construct */

  /**
   * @return array
   */
  public function version( )
  {

    $version = array();

    $version['major'] = 1;
    $version['minor'] = 0;
    $version['release'] = 0;

    return $version;

  }//end public function version */

  /**
   * @param string $text
   */
  public function out( $text )
  {

    echo $text.NL;

  }//end public function out */

  /**
   */
  public function publish(  )
  {

    $content = $this->tpl->render();
    $this->tpl->sendHeader();
    
    $encode = function_exists('gzencode');

    if ($encode && isset($_SERVER ['HTTP_ACCEPT_ENCODING']) && strstr($_SERVER ['HTTP_ACCEPT_ENCODING'], 'gzip')) {
        // Tell the browser the content is compressed with gzip
        header("Content-Encoding: gzip");
        $out = gzencode($content);
    } else {
        $out = $content;
    }
    
    header('ETag: '.md5($out));
    header('Content-Length: '.strlen($out));
    header('Expires: Thu, 13 Nov 2179 00:00:00 GMT');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    
    echo $out;

  }//end public function publish */

  /**
   * @return TemplateWorkarea
   */
  public function getWorkArea()
  {
    return $this->tpl->getWorkArea();
  }//end public function getWorkArea */

  /**
   * Einfach ausgabe des Textes
   * @param string $text
   */
  public function in( )
  {

    return fgets(STDIN);

  }//end public function in */

  /**
   * Einfach ausgabe des Textes
   * @param string $text
   */
  public function inSecure( )
  {

    system('stty -echo');
    $password = trim(fgets(STDIN));
    system('stty echo');
    // add a new line since the users CR didn't echo
    echo "\n";

    return $password;

  }//end public function in */


  /**
   * @param string $info
   */
  public function debug( $info )
  {

    $this->out( 'DEBUG: '.$info );

  }//end public function debug */

  /**
   * @param string $info
   */
  public function info( $info )
  {

    $this->out( 'INFO: '.$info );

  }//end public function info */

  /**
   * @param string $warning
   */
  public function warning( $warning )
  {
    $this->out( 'WARNING: '.$warning );

  }//end public function warning */

  /**
   * @param string $error
   */
  public function error( $error )
  {
    $this->out( 'ERROR: '.$error );

  }//end public function warning */

  /**
   * @param string $question
   * @return boolean
   */
  public function question( $question )
  {

    $this->out( $question.' ( y / n )' );
    $in = $this->in();

    if( 'y' == strtolower(trim($in)) )
      return true;
    else
      return false;


  }//end public function question */

  /**
   * @param string $text
   * @param string $icon
   */
  public function notification( $text, $icon = "info" )
  {
    $this->out( $text.' ( return to proceed )' );

  }//end public function info */

  /**
   * @param string $fileName
   */
  public function fileSelector( $fileName )
  {

    $this->out( "Please insert Filename" );

    if( $fileName )
      $this->out( "Default would be: {$fileName}" );

    $in = $this->in( );

    if( '' != trim($in) )
      return $in;
    else
      return $fileName;


  }//end public function fileSelector */


  /**
   * @param string $folderName
   */
  public function folderSelector( $folderName )
  {

    $this->out( "Please insert Foldername" );

    if( $folderName )
      $this->out( "Default would be: {$folderName}" );

    $in = $this->in( );

    if( '' != trim($in) )
      return $in;
    else
      return $folderName;

  }//end public function folderSelector */

  /**
   * @param string $text
   * @param string $title
   * @param string $entryText
   */
  public function readText
  (
    $text,
    $title = "Insert Value",
    $entryText  = null,
    $required   = false,
    $validator  = null
  ) {

    $userInput = null;


    $this->out( $text );

    if( $entryText )
      $this->out( "Default would be: {$entryText}" );

    $allFine = false;

    while ( !$allFine )
    {

      $userInput = $this->in();

      if( '' == $userInput )
      {
        if( $required )
        {
          continue;
        }
      }

      if( $validator )
      {
        if( $error = $validator( $userInput ) )
        {
          $this->error( $error );
          continue;
        }
      }

      $allFine = true;

    }

    return $userInput;

  }//end public function readText */

  /**
   * @param string $text
   * @param string $title
   */
  public function readPassword( $text, $title = "Insert Password" )
  {

    $this->out( $text );

    system('stty -echo');
    $password = trim(fgets(STDIN));
    system('stty echo');
    // add a new line since the users CR didn't echo
    echo "\n";

    return $password;

  }//end public function readPassword */

  /**
   * @param string $text
   * @param string $data
   * @param string $head
   */
  public function dataList
  (
    $title,
    array $data,
    array $head = array(),
    UiDimension $dimension = null
  )
  {

    // ok ne leere liste ohne head kann man halt nicht anzeigen
    if( !$data && !$head )
      return null;

    if( !$head )
    {
      $head = array_keys($data[0]);
    }

    $this->out( $title );

    $columns = array();
    foreach( $head as $headCol )
    {
      $columns[] = str_pad( substr($headCol, 0,20), 20, ' ' ).'|';
    }
    echo implode( '', $columns  )."\n";

    foreach( $data as $row )
    {
      foreach( $row as $cell )
      {
        echo str_pad( substr($cell, 0,20), 20, ' ' ).'|';
      }

      echo "\n";

    }
    echo "\n";

    return $this->in();

  }//end public function dataList */

  /**
   * @param string $text
   * @param string $data
   * @param string $head
   */
  public function radioList
  (
    $title,
    array $data,
    array $head = array(),
    UiDimension $dimension = null
  )
  {

    // ok ne leere liste ohne head kann man halt nicht anzeigen
    if( !$data && !$head )
      return null;

    if( !$head )
    {
      $head = array_keys($data[0]);
    }

    $this->out( $title );

    $columns = array();
    foreach( $head as $headCol )
    {
      $columns[] = str_pad( substr($headCol, 0,20), 20, ' ' ).'|';
    }
    echo implode( '', $columns  )."\n";


    foreach( $data as $row )
    {
      foreach( $row as $cell )
      {
        echo str_pad( substr($cell, 0,20), 20, ' ' ).'|';
      }

      echo "\n";

    }
    echo "\n";

    return $this->in();

  }//end public function radioList */


  /**
   * @param string $info
   * @param string $file
   * @param string $checkboxText
   */
  public function dialog( $title, $file, $checkboxText = null )
  {

    $this->out( $title );
    $this->out( file_get_contents(realpath( './'.$file )) );
    $this->out( $checkboxText.' (yes or no)' );

    $userInp = strtolower($this->in()) ;

    return ( 'yes' == $userInp );

  }//end public function dialog */

}//end class UiConsoleHttp
