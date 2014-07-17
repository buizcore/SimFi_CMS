<?php



/**
 * @package com.BuizCore
 * @subpackage SimFi
 *
 */
class RequestHttp implements IsARequest
{
////////////////////////////////////////////////////////////////////////////////
// Attribute
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var string
   */
  public $service = null;

  /**
   * @var string
   */
  public $action = null;

  /**
   * @var boolean
   */
  public $ajax = false;

  /**
   * @var array
   */
  protected $browserInfo = array();

  /**
   * @var string
   */
  protected $serverAddress = null;

////////////////////////////////////////////////////////////////////////////////
// Init
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   */
  public function __construct()
  {

    $this->init();

    if( isset( $_GET['serv'] ) ) {
      $tmp = explode( ':', $_GET['serv'] );

      if( 2 == count( $tmp ) ) {
        $this->service = FormatString::subToCamelCase( $tmp[0] );
        $this->action  = $tmp[1];
      } else {
        $this->service = FormatString::subToCamelCase( $tmp[0] );
        $this->action  = 'default';
      }

    } else {
      $this->service = 'Page';
      $this->action  = 'default';
    }

    if (isset($_GET['ajax'])) {
      $this->ajax = true;
    }


  }//end public function __construct */

  /**
   *
   */
  public function init()
  {

    // bei PUT requests PUT in $_POST schieben
    if( $this->method( 'PUT' ) ) {
      mb_parse_str(file_get_contents("php://input"),$_POST);
    }

  }//end public function init */

  /**
   * @param string $key
   * @return RequestSubHttp
   *
   */
  public function getSubRequest( $key, $messages )
  {

    if( !isset( $_POST[$key] ) ) {

      return null;

    } else {

      return new RequestSubHttp(
        $this,
        $_POST[$key],
        (isset($_FILES[$key])?$_FILES[$key]:array()),
          $messages
      );
    }

  }//end public function getSubRequest */

////////////////////////////////////////////////////////////////////////////////
// Getter & Setter Methodes
////////////////////////////////////////////////////////////////////////////////


  /**
   * Funktion zum testen ob eine bestimmte Urlvariable existiert
   *
   * @param string Key Name der zu erfragende $_GET Variable
   * @return bool
   */
  public function paramExists( $key )
  {

    if( isset( $_GET[$key] ) )
    {
      return true;
    }
    else
    {
      return false;
    }
  } // end public function paramExists */

  /**
  * Daten einer bestimmten Urlvariable erfragen
  *
  * @param string Key Name der zu erfragende $_GET Variable
  * @return TArray
  */
  public function paramList( $key, $validator )
  {

    $response = $this->getResponse();

    $filter = Validator::getActive();
    $filter->clean(); // first clean the filter


    $paramList = new TArray();

    if( isset( $_GET[$key] ) )
    {
      $data = $_GET[$key];

      if( !is_array( $data ) )
      {
        return $paramList;
      }
    }
    else
    {
      return $paramList;
    }

    $fMethod = 'add'.ucfirst( $validator );

    // clean only one
    foreach( $data as $key => $value )
    {
      $error = $filter->$fMethod( $key, $value );
      if( !$error )
      {
        $paramList->$key = $filter->getData( $key );
      }
      else
      {
        $response->addError( $error ) ;
        continue;
      }
    }

    return $paramList;

  } // end public function paramList */

  /**
  * Daten einer bestimmten Urlvariable erfragen
  *
  * @param string Key Name der zu erfragende $_GET Variable
  * @return string
  */
  public function param( $key, $validator = null )
  {

    if( isset( $_GET[$key] ) )
    {
      $data = $_GET[$key];

      return $data;
    }
    else
    {
      return null;
    }

  } // end public function param */

 /**
  * Hinzufügen oder ersetzten einer Variable in der URL
  *
  * @param string $key Name des Urlkeys
  * @param string $data Die Daten für die Urlvar
  * @return bool
  */
  public function addParam( $key, $data = null  )
  {

    if( is_array($key) )
    {
      $_GET = array_merge($_GET,$key);
    }
    else
    {
      $_GET[$key] = $data;
    }

  } // end public function addParam */

  /**
   * remove some variables from the url
   *
   */
  public function removeParam( $key )
  {
    if( isset( $_GET[$key]) )
    {
      unset($_GET[$key]);
    }

  }//end public function removeParam */


////////////////////////////////////////////////////////////////////////////////
//
////////////////////////////////////////////////////////////////////////////////

  /**
   * Abfragen des Status einer POST Variable
   *
   * @param string $key Name der zu prüfenden Variable
   * @param string $subkey
   *
   * @return bool
   */
  public function dataExists( $key )
  {

    if( isset( $_POST[$key] ) )
    {
      return true;
    }
    else
    {
      return false;
    }

  } // end public function dataExists */

  /**
   * Abfragen des Status einer POST Variable
   *
   * @param string Key Name der zu prüfenden Variable
   * @return bool
   */
  public function dataSearchIds( $key )
  {

    if( !isset( $_POST[$key] ) || !is_array( $_POST[$key] ) )
      return array();

    $keys = array_keys( $_POST[$key] );

    $tmp = array();

    foreach( $keys as $key )
    {
      if( 'id_' == substr( $key , 0, 3 ) )
        $tmp[] = $key;
    }

    return $tmp;

  } // end public function dataSearchIds */

  /**
   * Abfragen des Status einer POST Variable
   *
   * @param string Key Name der zu prüfenden Variable
   * @return bool
   */
  public function paramSearchIds( $key )
  {

    if( !isset( $_GET[$key] ) || !is_array( $_GET[$key] ) )
      return array();

    $keys = array_keys( $_GET[$key] );
    $tmp  = array();

    foreach( $keys as $key )
    {
      if( 'id_' == substr( $key , 0, 3 ) )
        $tmp[] = $key;
    }

    return $tmp;

  } // end public function paramSearchIds */

  /**
  * Auslesen einer Postvariable
  *
  * @param string $key
  * @param string $validator
  *
  * @return array
  */
  public function data( $key = null, $validator = null, $subData = null  )
  {

    if ($subData) {
        
        return isset( $_POST[$key][$subData] )
        ? $_POST[$key][$subData]
        : null;
        
    } else {
        return isset( $_POST[$key] )
        ? $_POST[$key]
        : null;
    }
      


  } // end public function data */

  /**
   * remove some variables from the url
   */
  public function removeData( $key )
  {

    if( isset( $_POST[$key] ) ) {
      unset( $_POST[$key] );
    }

  }//end public function removeData */

  /**
   * request if one or more values are empty
   *
   * @param string Key Name der zu prüfenden Variable
   * @return bool
   */
  public function dataEmpty( $keys , $subkey = null )
  {

    if( $subkey )
    {
      if( is_array($keys) )
      {

        foreach( $keys as $key )
        {

          if( !isset( $_POST[$subkey][$key] ) )
          {
            return true;
          }

          if( trim($_POST[$subkey][$key]) == '' )
          {
            return true;
          }

          return false;

        }

      }
      else
      {

        if( !isset( $_POST[$subkey][$keys] ) )
        {
          return true;
        }

        if( trim($_POST[$subkey][$keys]) == '' )
        {
          return true;
        }

        return false;

      }

    }
    else
    {
      if( is_array($keys) )
      {

        foreach( $keys as $key )
        {

          if( !isset( $_POST[$key] ) )
            return true;

          if( trim($_POST[$key]) == '' )
            return true;

          return false;

        }

      }
      else
      {

        if( !isset( $_POST[$keys] ) )
          return true;

        if( trim($_POST[$keys]) == '' )
          return true;

        return false;

      }

    }

  } // end public function dataEmpty */

/*//////////////////////////////////////////////////////////////////////////////
//
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * Enter description here...
   *
   * @param string $fMethod
   * @param array $data
   * @return array
   */
  protected function validateArray( $fMethod , $data )
  {

    $filter = Validator::getActive();

    /// TODO checken ob das hier eine Fehlerquelle ist
    /// da validate Array ja rekursiv augerufen werdden kann
    //$filter->clean();

    $back = array();

    // Clean all the same way
    // Good architecture :-)
    foreach( $data as $key => $value )
    {
      if( is_array($value) )
      {
        $back[$key] = $this->validateArray( $fMethod , $value );
      }
      else
      {
        // jedes mal ein clean
        $filter->clean();
        $filter->$fMethod($key,$value);

        $tmp = $filter->getData();
        $back[$key] = $tmp[$key];
      }
    }

    return $back;

  }//end protected function validateArray */

/*//////////////////////////////////////////////////////////////////////////////
//
//////////////////////////////////////////////////////////////////////////////*/

  /**
  * request if we have a cookie with this name
  *
  * @param string Key Name des zu testenden Cookies
  * @return bool
  */
  public function issetCookie( $key  )
  {

    return isset( $_COOKIE[$key] );

  } // end public function issetCookie */

 /**
  * Abfragen einer bestimmten Postvariable
  *
  * @param string Key Name des angefragten Cookies
  * @return string
  */
  public function cookie( $key = null , $validator = null, $message = null )
  {

    if( is_null($key) )
    {
      return Db::addSlashes( $_COOKIE );
    }

    if($validator)
    {
      $filter = Validator::getActive();
      $filter->clean(); // first clean the filter

      if(isset( $_COOKIE[$key] ))
      {
        $fMethod = 'add'.ucfirst($validator);
        $filter->$fMethod($_COOKIE[$key],$key);

        return Db::addSlashes( $filter->getData($key) );
      }
      else
      {
        return null;
      }
    }
    else
    {
      if(isset( $_COOKIE[$key] ))
      {
         return Db::addSlashes($this->cookie[$key]);
      }
      else
      {
        return null;
      }
    }
  } // end public function cookie */

 /**
  * Request if a File Upload Exists
  *
  * @param string Key Name des zu testenden Cookies
  * @return bool
  */
  public function fileExists( $key )
  {

    if( isset( $_FILES[$key] ) )
    {
      return true;
    }
    else
    {
      return false;
    }

  } // end public function fileExists */

 /**
  * Request if a File Upload Exists
  *
  * @param string $key
  * @param string $type
  * @param string $subkey
  * @param string $message
  *
  * @return LibUploadFile
  */
  public function file( $key = null, $type = null, $subkey = null, $message = null )
  {

    if( is_null($key) )
    {
      return $_FILES;
    }

    $filter = Validator::getActive();
    $filter->clean(); // first clean the filter

    if( $subkey )
    {
      // asume this was just an empty file
      if( !isset($_FILES[$subkey]) || '' == trim($_FILES[$subkey]['name'][$key]) )
      {
        $data = null;
      }
      else
      {
        $data = array();
        $data['name']     = $_FILES[$subkey]['name'][$key];
        $data['type']     = $_FILES[$subkey]['type'][$key];
        $data['tmp_name'] = $_FILES[$subkey]['tmp_name'][$key];
        $data['error']    = $_FILES[$subkey]['error'][$key];
        $data['size']     = $_FILES[$subkey]['size'][$key];
      }
    }
    else
    {
      // asume this was just an empty file
      if( !isset($_FILES[$key]) || '' == trim($_FILES[$key]['name']) )
      {
        $data = null;
      }
      else
      {
        $data = $_FILES[$key];
      }
    }

    if( !$data )
      return null;

    if( $type )
    {
      $classname = 'LibUpload'.SParserString::subToCamelCase($type);

      if( !Webfrap::classLoadable( $classname ) )
        throw new LibFlow_Exception( 'Requested nonexisting upload type: '.$classname );

      return new $classname( $data, $key );

    }
    else
    {
      return new LibUploadFile( $data );
    }

  } // end public function file */

  /**
  * request if we have a cookie with this name
  *
  * @param string Key Name des zu testenden Cookies
  * @return bool
  */
  public function serverExists( $key  )
  {

    if( isset( $_SERVER[$key] ) )
    {
      return true;
    }
    else
    {
      return false;
    }
  } // end public function serverExists */

  /**
  * Abfragen einer bestimmten Postvariable
  *
  * @param string Key Name des angefragten Cookies
  * @return string
  */
  public function server( $key = null , $validator = null, $message = null )
  {

    if( is_null( $key ) )
      return Db::addSlashes( $_SERVER );

    if( $validator )
    {
      $filter = Validator::getActive();
      $filter->clean(); // first clean the filter

      if( isset( $_SERVER[$key] ) )
      {
        $fMethod = 'add'.ucfirst( $validator );
        $filter->$fMethod( $_SERVER[$key], $key );

        return Db::addSlashes( $filter->getData( $key ) );
      }
      else
      {
        return null;
      }
    }
    else
    {
      if( isset( $_SERVER[$key] ) )
      {
        return Db::addSlashes( $_SERVER[$key] );
      }
      else
      {
        return null;
      }
    }

  } // end public function server */

  /**
  * request if we have a cookie with this name
  *
  * @param string Key Name des zu testenden Cookies
  * @return bool
  */
  public function envExists( $key  )
  {
    return isset( $_ENV[$key] );
  } // end public function envExists */

  /**
  * Abfragen einer bestimmten Postvariable
  *
  * @param string $key name of the requested env value
  * @param string $validator the validatorname
  * @return mixed
  */
  public function env( $key = null , $validator = null, $message = null )
  {

    if( is_null($key) )
    {
      return Db::addSlashes($_ENV);
    }

    if( $validator )
    {

      $filter = Validator::getActive();
      $filter->clean(); // first clean the filter

      if( isset( $_ENV[$key] ) )
      {
        if(Log::$levelDebug)
          Log::debug('env['.$key.'] ist gesetzt' );

        $fMethod = 'add'.ucfirst($validator);
        $filter->$fMethod($_ENV[$key],$key);

        return Db::addSlashes( $filter->getData( $key ) );

      }
      else
      {
        return null;
      }

    }
    else
    {

      if( isset( $_ENV[$key] ) )
      {
        return Db::addSlashes( $_SERVER[$key] );
      }
      else
      {
        return null;
      }

    }

  } // end public function env */




////////////////////////////////////////////////////////////////////////////////
// Get Client Informations
////////////////////////////////////////////////////////////////////////////////

  /**
   * Erfragen des Browser types
   * @return string
   */
  public function getBrowser()
  {

    if( isset( $this->browserInfo['name'] ) )
      return $this->browserInfo['name'];

    $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);

    if( preg_match( '/opera/', $userAgent ) )
    {
      $this->browserInfo['name'] = 'opera';
    }
    elseif( preg_match( '/webkit/', $userAgent ) )
    {
      $this->browserInfo['name'] = 'safari';
    }
    elseif( preg_match( '/msie/', $userAgent ) )
    {
      $this->browserInfo['name'] = 'msie';
    }
    elseif( preg_match( '/chrome/', $userAgent) )
    {
      $this->browserInfo['name'] = 'chrome';
    }
    elseif( preg_match( '/mozilla/', $userAgent ) && !preg_match( '/compatible/', $userAgent ) )
    {
      $this->browserInfo['name'] = 'mozilla';
    }
    else
    {
      $this->browserInfo['name'] = 'unrecognized';
    }

    return $this->browserInfo['name'];

  }//end public function getBrowser */

  /**
   * Erfragen der Browserversion
   * @return string
   */
  public function getBrowserVersion()
  {
    if( isset($this->browserInfo['version'])  )
      return $this->browserInfo['version'];

    $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);

    // What version?
    if (preg_match('/.+(?:rv|it|ra|ie)[\/: ]([\d.]+)/', $userAgent, $matches))
    {
      $this->browserInfo['version'] = $matches[1];
    }
    else
    {
      $this->browserInfo['version'] = 'unknown';
    }

    return $this->browserInfo['version'];

  }//end public function getBrowserVersion */

  /**
   * Abfragen der Client Plattform
   * @return string
   */
  public function getPlatform()
  {
    if( isset($this->browserInfo['platform'])  )
      return $this->browserInfo['platform'];

    $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);

    // Running on what platform?
    if (preg_match('/linux/', $userAgent))
    {
      $this->browserInfo['platform'] = 'linux';
    }
    else if (preg_match('/macintosh|mac os x/', $userAgent))
    {
      $this->browserInfo['platform'] = 'mac';
    }
    else if (preg_match('/windows|win32/', $userAgent))
    {
      $this->browserInfo['platform'] = 'windows';
    }
    else
    {
      $this->browserInfo['platform'] = 'unrecognized';
    }

    return $this->browserInfo['platform'];

  }//end public function getPlatform */

  /**
   *
   * Enter description here...
   * @return string
   */
  public function getUseragent()
  {
    return strtolower( $this->server('HTTP_USER_AGENT') );
  }//end public function getUseragent */

  /**
   *
   * Enter description here...
   * @return string
   */
  public function getClientIp()
  {
    return $this->server( 'HTTP_HOST' );
  }//end public function getClientIp */

  /**
   *
   * Enter description here...
   * @return string
   */
  public function getEncoding()
  {
    // 'gzip,deflate'
    return explode( ',', $this->server('HTTP_ACCEPT_ENCODING') );
  }//end public function getClientIp */

  /**
   *
   * Enter description here...
   * @return string
   */
  public function getClientLanguage()
  {

    // 'de-de,de;q=0.8,en-us;q=0.5,en;q=0.3'
    return explode( ';', $this->server('HTTP_ACCEPT_LANGUAGE') );
  }//end public function getClientLanguage */

  /**
   *
   * Enter description here...
   * @return string
   */
  public function getCharset()
  {
    // 'ISO-8859-1,utf-8;q=0.7,*;q=0.7'
    return explode( ';', $this->server('HTTP_ACCEPT_CHARSET') ) ;
  }//end public function getCharset */


  /**
   *
   * Enter description here...
   * @return string
   */
  public function getClientRefer()
  {
    return $this->server('HTTP_REFERER');
  }//end public function getClientHref */


////////////////////////////////////////////////////////////////////////////////
// Static Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * get the request method
   *
   * @return string
   */
  public function method( $requested = null )
  {

    if( !isset( $_SERVER['REQUEST_METHOD'] ) )
    {
      Error::report( 'Got no request method, asumig this was a get request' );
      $method = 'GET';
    }
    else
    {
      $method = strtoupper($_SERVER['REQUEST_METHOD']);
    }

    //this should always be uppper, but no risk here
    if( !$requested )
      return $method;
    else
    {
      if( is_array( $requested ) )
      {
        foreach( $requested as $reqKey )
        {
          if( $method == $reqKey )
            return true;

        }

        return false;
      }
      else
      {
        return $requested == $method ? true:false;
      }
    }


  }//end public function method */

  /**
   * @param [string] $methodes
   *
   * @return string
   */
  public function inMethod( $methodes )
  {

    if( !isset( $_SERVER['REQUEST_METHOD'] ) )
    {
      $method = 'GET';
    }
    else
    {
      $method = strtoupper( $_SERVER['REQUEST_METHOD'] );
    }

    //this should always be uppper, but no risk here
    return in_array( $method, $methodes );

  }//end public function inMethod */

  /**
   * @return boolean
   */
  public function isAjax()
  {
    return isset($_GET['rqt']);
  }//end public function isAjax */


  /**
   * @return boolean
   */
  public function getResource()
  {
    return $_SERVER['QUERY_STRING'];
  }//end public function getResource */


  /**
   * @return string
   */
  public function getServerName()
  {

    return $_SERVER['SERVER_NAME'];

  }//end public function getServerName */

  /**
   * @param boolean $forceHttps
   * @return string
   */
  public function getServerAddress( $forceHttps = false )
  {

    if( !$this->serverAddress )
    {

      $this->serverAddress = ( (isset($_SERVER['HTTPS']) && 'on' == $_SERVER['HTTPS']) || $forceHttps )
        ? 'https://'
        : 'http://';

      $this->serverAddress .= $_SERVER['SERVER_NAME'];

      if( isset( $_SERVER['HTTPS'] ) && 'on' == $_SERVER['HTTPS'] )
      {
        if( $_SERVER['SERVER_PORT'] != '443' )
        {
          $this->serverAddress .= ':'.$_SERVER['SERVER_PORT'];
        }
      }
      else
      {
        if( $_SERVER['SERVER_PORT'] != '80' )
        {
          $this->serverAddress .= ':'.$_SERVER['SERVER_PORT'];
        }
      }

      $this->serverAddress .='/'.mb_substr( $_SERVER['REQUEST_URI'] , 0 , strrpos($_SERVER['REQUEST_URI'],'/')+1);

      $length = strlen($this->serverAddress);

      if( '/' != $this->serverAddress[($length-1)] )
        $this->serverAddress .= '/';

    }

    return $this->serverAddress;

  }//end public function getServerAddress */

}// end class LibRequestPhp


