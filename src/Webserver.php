<?php




  
/**
 * Verwaltung des Apache Webservers
 * @package WebFrap
 * @subpackage WebExpert
 */
class Webserver
{
  
  /**
   * @param array $gwRepos
   * @param string $rootPath
   * @param string $universe
   */
  public static function createGwVhosts( $gwRepos, $rootPath, $universe )
  {
    
    foreach( $gwRepos as $gwPrj )
    {
      $kvData = array
      (
        '{@host@}'         => $gwPrj['vhost']['host'],
        '{@port@}'         => $gwPrj['vhost']['port'],
        '{@server_admin@}' => $gwPrj['vhost']['server_admin'],
        '{@server_name@}'  => $gwPrj['vhost']['server_name'],
        '{@root_path@}'    => $rootPath,
        '{@gw_project@}'   => $gwPrj['name'],
        '{@universe@}'     => $universe,
        '{@icon_project@}' => $gwPrj['vhost']['icon_project'],
        '{@theme_project@}'=> $gwPrj['vhost']['theme_project'],
      );

      
      Fs::template
      ( 
        "/etc/apache2/sites-available/{$gwPrj['vhost']['server_name']}.vhost", 
        WEBX_PATH.'data/apache/vhost.tpl',
        $kvData 
      );
      
      Webserver::activatePage( $gwPrj['vhost']['server_name'] );
      
    }
    
    Webserver::reload();
    
  }//end public static function createGwVhosts */
  
  /**
   */
  public static function reload( )
  {
    
    Process::execute( "/etc/init.d/apache2 reload" );
    
  }//end public static function reload */
  
  /**
   */
  public static function restart( )
  {
    
    Process::execute( "/etc/init.d/apache2 restart" );
    
  }//end public static function restart */
  
  /**
   */
  public static function start( )
  {
    
    Process::execute( "/etc/init.d/apache2 start" );
    
  }//end public static function start */
  
  /**
   */
  public static function stop( )
  {
    
    Process::execute( "/etc/init.d/apache2 stop" );
    
  }//end public static function stop */ 

  /**
   * @param string $pageName
   */
  public static function activatePage( $pageName )
  {
    
    Process::execute( "a2enpage {$pageName}" );
    
  }//end public static function activatePage */
  
  /**
   * @param string $pageName
   */
  public static function deactivatePage( $pageName )
  {
    
    Process::execute( "a2dissite {$pageName}" );
    
  }//end public static function deactivatePage */
  
  /**
   * @param string $modName
   */
  public static function activateModule( $modName )
  {
    
    Process::execute( "a2enmod {$modName}" );
    
  }//end public static function activateModule */
  
  /**
   * @param string $modName
   */
  public static function deactivateModule( $modName )
  {
    
    Process::execute( "a2dismod {$modName}" );
    
  }//end public static function deactivateModule */

}//end class Webserver */
