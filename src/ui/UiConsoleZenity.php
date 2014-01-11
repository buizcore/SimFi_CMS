<?php



/**
 * Ausgabe von UI elementen in die shell
 * 
 * @subpackage web_expert
 */
class UiConsoleZenity
  extends UiConsole
  implements IsAConsole
{

  /**
   * @var string
   */
  public $type = 'zenity';

  /**
   * @var array
   */
  private $version = array();

  /**
   * @var UiConsoleZenity
   */
  private static $default = null;

  /**
   * @return Zenity
   */
  public static function getActive()
  {

    if( !self::$default )
      self::$default = new UiConsoleZenity();

    return self::$default;

  }//end public static function getActive */

  /**
   * @return array
   */
  public function version( )
  {

    $version = array();
    $tmp = explode( '.', Process::execute( 'zenity  --version' ) ) ;

    $version['major'] = isset($tmp[0])?:0;
    $version['minor'] = isset($tmp[1])?:0;
    $version['release'] = isset($tmp[2])?:0;

    return $version;

  }//end public function version */

  /**
   * @param string $text
   */
  public function out( $text )
  {

    echo $text."\n";

  }//end public function out */

  /**
   * Einfach ausgabe des Textes
   * @param string $text
   */
  public function in( )
  {

    return fgets(STDIN);

  }//end public function in */

  /**
   * @param string $info
   */
  public function debug( $info )
  {
    echo 'DEBUG: '.$info."\n";

  }//end public function debug */

  /**
   * @param string $info
   */
  public function info( $info )
  {
    echo 'INFO: '.$info."\n";
    return Process::execute( 'zenity  --info  --timeout 10 --text "'.str_replace('"','\"',$info).'"' );

  }//end public function info */

  /**
   * @param string $warning
   */
  public function warning( $warning )
  {

    echo 'WARNING: '.$warning."\n";
    return Process::execute( 'zenity  --warning --text "'.str_replace('"','\"',$warning).'"' );

  }//end public function warning */

  /**
   * @param string $warning
   */
  public function error( $error )
  {
    echo 'ERROR: '.$error."\n";
    return Process::execute( 'zenity  --error --text "'.str_replace('"','\"',$error).'"' );

  }//end public function warning */

  /**
   * @param string $question
   * @return boolean
   */
  public function question( $question )
  {

    return (boolean)trim(Process::execute( 'if ! zenity --question --text "'.str_replace('"','\"',$question).'"; then echo 0; else echo 1; fi' ));

  }//end public function question */

  /**
   * @param string $text
   * @param string $icon
   */
  public function notification( $text, $icon = "info" )
  {

    return Process::execute( 'zenity --notification --text "'.str_replace('"','\"',$text).'" --window-icon="'.$icon.'"' );

  }//end public function info */

  /**
   * @param string $fileName
   */
  public function fileSelector( $fileName )
  {

    return Process::execute
    (
      'echo zenity  --file-selection --filename "'.str_replace('"','\"',$fileName).'" '
    );

  }//end public function fileSelector */


  /**
   * @param string $folderName
   */
  public function folderSelector( $folderName )
  {

    return Process::execute
    (
      'echo zenity  --file-selection --directory --filename "'.str_replace('"','\"',$folderName).'" '
    );

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
  )
  {

    $userInput = null;

    if( $entryText )
      $entryText = '--entry-text="'.str_replace('"','\"',$entryText).'"';

    $allFine = false;

    while ( !$allFine )
    {

      // alte eingaben
      if( !is_null($userInput) )
        $entryText = '--entry-text="'.str_replace('"','\"',$userInput).'"';

      $userInput = trim(Process::execute( 'zenity --entry --text="'.str_replace('"','\"',$text).'" '.$entryText.' --title="'.str_replace('"','\"',$title).'"' ));

      if( '' == $userInput )
      {
        if( $required )
        {
          continue;
        }
      }

      if( $validator )
      {
        if( $error = $validator($userInput) )
        {
          $this->error($error);
          continue;
        }
      }

      $allFine = true;

    }

    return $userInput;

  }//end public function readText */

  /**
   * Passwort lesen
   * @param string $text
   * @param string $title
   */
  public function readPassword( $text, $title = "Insert Password" )
  {

    return trim
    (
      Process::execute
      (
        'zenity --entry --hide-text --text="'.str_replace('"','\"',$text)
          .'" --title="'.str_replace('"','\"',$title).'"'
      )
    );

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

    $columns = array();
    foreach( $head as $headCol )
    {
      $columns[] = ' --column="'.str_replace('"','\"',$headCol).'" ';
    }
    $columns = implode( ' ', $columns  );

    $dataBlock = '';
    foreach( $data as $row )
    {
      foreach( $row as $cell )
      {
        $dataBlock .= ' "'.str_replace('"','\"',$headCol).'"';
      }
    }

    return Process::execute
    (
      'zenity --list --title "'.str_replace('"','\"',$title).'"  '.$columns.$dataBlock.$dimension
    );

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

    $columns = array();
    foreach( $head as $headCol )
    {
      $columns[] = ' --column="'.str_replace('"','\"',$headCol).'" ';
    }
    $columns = implode( ' ', $columns  );

    $dataBlock = '';
    foreach( $data as $row )
    {
      foreach( $row as $cell )
      {
        $dataBlock .= ' "'.str_replace('"','\"',$cell).'"';
      }
    }

    return Process::execute
    (
      'zenity --list --radiolist --title "'.str_replace('"','\"',$title).'"  '.$columns.$dataBlock.$dimension
    );

  }//end public function radioList */


  /**
   * @param string $info
   * @param string $file
   * @param string $checkboxText
   */
  public function dialog( $title, $file, $checkboxText = null )
  {

    if( $checkboxText )
      $checkboxText = ' --checkbox "'.str_replace('"','\"',$checkboxText).'"';

    $realpath = realpath( './'.$file );

    return Process::execute
    (
      'zenity --text-info --filename='.$realpath.' --title "'.str_replace('"','\"',$title).'"'.''.'; echo $?;'
    );

  }//end public function dialog */

}//end class UiConsoleZenity


$console = new UiConsoleZenity();
UiConsole::setActive( $console );
Console::setActive( $console );
