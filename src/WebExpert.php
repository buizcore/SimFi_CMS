<?php


///
/// NEIN, DIES DATEI ERHEBT NICHT DEN ANSPRUCH OOP ZU SEIN.
/// ES IS EXPLIZIT AUCH NICHT ALS OOP GEWOLLT.
/// DIE KLASSEN WERDEN LEDIGLICH ALS CONTAINER ZUM ORGANISIEREN DER FUNKTIONEN VERWENDET.
/// JA DAS IST VIEL CODE FÜR EINE DATEI, NEIN ES IST KEIN PROBLEM
/// NEIN ES IST WIRKLICH KEIN PROBLEM, SOLLTE ES DOCH ZU EINEM WERDEN WIRD ES
/// GELÖST SOBALD ES EINS IST
/// Danke ;-)
///


/**
 * WebExpert Basisklasse
 * @package WebFrap
 * @subpackage WebExpert
 */
class WebExpert
{

  /**
   * @var string
   */
  const C_QUIT = 'quit';

  /**
   * @var string
   */
  const VERSION = '0.1';

  /**
   * @var int
   */
  const MAX_PACKAGE_LEVEL = 5;

  /**
   * @var int
   */
  public static $sequence = 0;

  /**
   * @var array
   */
  private static $classIndex = array();

  /**
   * @var array
   */
  private static $loadAble     = array();

  /**
   * @var Environment
   */
  public static $env     = null;

  /**
   * @var Conf
   */
  public static $conf     = null;

////////////////////////////////////////////////////////////////////////////////
// Logic
////////////////////////////////////////////////////////////////////////////////

  /**
   * Die Autoload Methode versucht anhand des Namens der Klassen den Pfad
   * zu erraten in dem sich die Datei befindet
   *
   * Dies Methode ist relativ Langsam und sollte nur beim Entwickeln genutzt
   * werden, Produktivsystemen geht das extrem auf die Performance
   *
   *
   * @param string $classname Name der Klasse
   */
  public static function pathAutoload( $classname )
  {

    $length = strlen($classname);

    $paths = array();
    $paths[] = PATH_ROOT.self::$conf->page_root.'/content/';
    $paths[] = PATH_ROOT.self::$conf->page_root.'/module/';
    $paths[] = PATH_ROOT.self::$conf->page_root.'/src/';

    $paths[] = WEBX_PATH.'src/';
    $paths[] = WEBX_PATH.'module/';


    foreach( $paths as $path ) {

      $requireMe = null;

      $parts = array();
      $start = 0;
      $end = 1;
      $package = '';

      if( file_exists( $path.$classname.'.php' ) ) {

        include $path.$classname.'.php' ;
        return;

      } else {

        // 3 Stufen Packages
        $level = 0;
        for( $pos = 1 ; $pos < $length  ; ++$pos ) {

          if(ctype_upper($classname[$pos]) ) {
            $package  .= strtolower( str_replace( '_','', substr( $classname, $start, $end  ) ) ).'/' ;
            $start    += $end;
            $end      = 0;
            ++$level;

            $file = realpath($path.$package.$classname.'.php');

            if( $file ) {
              self::$classIndex[$classname] = $file;
              syslog( LOG_DEBUG, 'FOUND: '.$path.$package.$classname.'.php ');
              include $file;
              return;
            } else {
              syslog( LOG_DEBUG, 'NOT FOUND: '.$path.$package.$classname.'.php ');
            }

            if( $level == self::MAX_PACKAGE_LEVEL )
              break;
          }
          ++$end;
        }

      }//end if( file_exists( $path.$classname.'.php' ) )

    }

  }//function public static function pathAutoload */

  /**
   * wrapper for class exists
   * cause class exists always throws an exception if the class not exists
   * @param string $classname
   * @return boolean
   */
  public static function classLoadable( $className )
  {

    if( !isset(self::$loadAble[$className]) )
    {
      try
      {
        $back = class_exists($className);
        self::$loadAble[$className] = $back;
        return $back;
      }
      catch( WebExpertException $e )
      {
        self::$loadAble[$className] = false;
        return false;
      }
    }
    else
    {
      return self::$loadAble[$className];
    }

  }//end function classLoadable */

  /**
   * wrapper for class exists
   * cause class exists always throws an exception if the class not exists
   * @param string $classname
   * @return boolean
   */
  public static function interfaceLoadable( $classname )
  {

    if( !isset(self::$loadAble[$classname]) )
    {
      try
      {
        $back = interface_exists($classname);
        self::$loadAble[$classname] = $back;
        return $back;
      }
      catch( WebExpertException $e )
      {
        self::$loadAble[$classname] = false;
        return false;
      }
    }
    else
    {
      return self::$loadAble[$classname];
    }

  }//end function interfaceLoadable */

  /**
   * using a wrapper so you can write your own unique method
   *
   * @return string
   */
  public static function uniqid()
  {
    return uniqid(mt_rand(), true);
  }//end public static function uniqid */

  /**
   * using a wrapper so you can write your own unique method
   *
   * @return string
   */
  public static function pwdHash( $pwd, $salt)
  {

    $hash = $salt.$pwd;

    // have phun calculating this type of hash for the whole database ;-)
    for( $pos = 0; $pos < 1000; ++$pos ){
      $hash = sha1($hash);
    }

    return $hash;

  }//end public static function pwdHash */


  /**
   * using a wrapper so you can write your own unique method
   * @param string $area
   * @return string
   */
  public static function tmpFile(  $area = null )
  {

    if( $area )
    {
      $tmpF = TMP_PATH.$area.'/'.str_replace( '.','_', uniqid(mt_rand(), true) ).'.tmp';
      return $tmpF;
    }

    return str_replace( '.','_', uniqid(mt_rand(), true) ) ;

  }//end public static function tmpFile */

  /**
   * @param string $prefix
   */
  public static function uuid( $prefix = '' )
  {

    $tmp = md5(uniqid(mt_rand(), true));

    return $prefix .substr($tmp,0,8).'-'.substr($tmp,8,4).'-'.substr($tmp,12,4)
      .'-'.substr($tmp,16,4).'-'.substr($tmp,20,12);

  }//end public static function uuid */

  /**
   * Aktuelle Timestamp im Datenbankformat
   * @return string
   */
  public static function timestamp(  )
  {

    return date( 'Y-m-d H:i:s' );

  }//end public static function timestamp */

  /**
   * SDBM Hash Algorithmus aus der Berkley Database
   * @param string $key
   * @return string
   */
  public static function keyHash( $key )
  {

    $mul = "65599"; // (1 << 6) + (1 << 16) - 1
    $mod = "18446744073709551616"; // 1 << 64

    $hash = "0";
    $keyLength = strlen($key);

    for ($i = 0; $i < $keyLength; ++$i) {
      $hash = bcmod(bcmul($hash, $mul), $mod);
      $hash = bcmod(bcadd($hash, ord($key[$i])), $mod);
    }

    return $hash;

  }//end public static function keyHash */

  /**
   * eine neue id aus der sequence erfragen
   *
   * @return int
   */
  public static function tmpid()
  {

    // fängt halt bei 1 an
    ++ self::$sequence;
    return 'tmp_'.self::$sequence;

  }//end public static function uniqid */

  /**
   * using a wrapper so you can write your own unique method
   * @param boolean $fullPath
   * @return string
   */
  public static function tmpFolder( $fullPath = false )
  {

    if( $fullPath )
      return TMP_PATH.str_replace( '.','_', uniqid(mt_rand(), true) ).'/' ;
    else
      return str_replace( '.','_', uniqid(mt_rand(), true) ) ;

  }//end public static function tmpFolder */

  /**
   * Erstellen eines Tmp Folders und Rückageb des Namens
   * @return string
   */
  public static function mkTmpFolder( )
  {

    $tmpF = TMP_PATH.str_replace( '.','_', uniqid(mt_rand(), true) ).'/';
    Fs::mkdir( $tmpF );

    return $tmpF;

  }//end public static function mkTmpFolder */

  /**
   * eine neue id aus der sequence erfragen
   *
   * @return int
   */
  public static function getRunId()
  {

    if( !self::$runkey ) {
      self::$runkey = time();
    }

    return self::$runkey;

  }//end public static function getRunId */

  /**
   * Checken ob die Syntax in einem File ok ist
   * @param string $fileName
   * @param string $errors
   * @return boolean
   */
  public static function checkSyntax( $fileName, &$errors )
  {

    $errors = Process::execute( "php -l {$fileName}"  );

    if( 'No syntax errors' == substr($errors, 0, 16 ) )
      return true;
    else
      return false;

  }//end public static function checkSyntax */

}//end class WebExpert */