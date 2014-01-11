<?php




  
/**
 * Klasse zum ausführen von Programmen
 * @package WebFrap
 * @subpackage WebExpert
 */
class Process
{
  
  /**
   * @param string $command
   */
  static function run( $command )
  {
    $result = '';
    if ($proc = popen("($command)2>&1","r") )
    {
      while (!feof($proc))
        echo fgets($proc, 1000);
  
      pclose($proc);

    }
    
  }//end static function run */
  
  /**
   * @param string $command
   */
  static function system( $command )
  {
   
    echo exec( $command );
    
  }//end static function system */
  
  /**
   * @param string $command
   */
  static function execute( $command )
  {
    $result = '';
    if ($proc = popen("($command)2>&1","r") )
    {
      while (!feof($proc))
        $result .= fgets($proc, 1000);
  
      pclose($proc);
  
      return $result;
    }
    
  }//end static function execute */
  
}//end class Process */
