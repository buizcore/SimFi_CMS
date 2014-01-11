<?php


/**
 * Betriebsystem spezifische elemente
 * 
 * @subpackage web_expert
 */
class ProtocolWriter
{

  /**
   * @var string
   */
  private $fileRes = null;

////////////////////////////////////////////////////////////////////////////////
// Object logic
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $fileName
   */
  public function __construct( $fileName )
  {

    Fs::touch( $fileName );

    $this->fileRes = fopen( $fileName, 'w' );

  }//end public function __construct */

  /**
   *
   */
  public function __destruct()
  {
    if( is_resource($this->fileRes) )
      fclose($this->fileRes);
  }//end public function __destruct */

  /**
   *
   */
  public function close()
  {
    if( is_resource($this->fileRes) )
      fclose($this->fileRes);
  }//end public function close */

  /**
   * @param string $msg
   */
  public function write( $msg )
  {

    fwrite( $this->fileRes, $msg."\n" );

  }//end public function write */

 /**
   * @param string $msg
   */
  public function head( $msg )
  {


    $head = <<<HEAD
-------------------------------------------------------------------------------
- {$msg}
-------------------------------------------------------------------------------
HEAD;

    $this->write( $head );

  }//end public function head */

  /**
   * @param string $msg
   */
  public function subHead( $msg )
  {

    $head = <<<HEAD
-
- {$msg}
-------------------------------------------------------------------------------
HEAD;

    $this->write( $head );

  }//end public function subHead */

  /**
   * @param string $msg
   */
  public function foot( $msg )
  {

    $head = <<<HEAD
*******************************************************************************
* {$msg}
*******************************************************************************
HEAD;

    $this->write( $head );

  }//end public function foot */

  /**
   * @param string $msg
   */
  public function line( $msg )
  {

    $this->write( $msg );

  }//end public function line */

  /**
   * @param string $msg
   */
  public function info( $msg )
  {

    $this->write( 'INFO: '.$msg );

  }//end public function info */

  /**
   * @param string $msg
   */
  public function warning( $msg )
  {

    $this->write( 'WARN: '.$msg );

  }//end public function warning */

  /**
   * @param string $msg
   */
  public function error( $msg )
  {

    $this->write( 'ERROR: '.$msg );

  }//end public function error */

  /**
   * @param string $msg
   */
  public function fatal( $msg )
  {

    $this->write( 'FATAL: '.$msg );

  }//end public function error */


}//end class Protocol */