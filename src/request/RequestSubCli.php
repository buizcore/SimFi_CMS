<?php



/**
 * @package com.BuizCore
 * @subpackage SimFi
 *
 */
class RequestSubCli
  implements IsARequest
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
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
   * @var array
   */
  public $params = array();

  /**
   * @var array
   */
  public $data = array();

  /**
   * @var IsARequest
   */
  public $request = null;

////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $command
   * @param IsARequest $request
   */
  public function __construct( $command, $request )
  {

    $this->request = $request;

    $urlData = parse_url( $command );
    $params  = array();

    if( isset( $urlData['query'] ) )
      parse_str( $urlData['query'], $params );

    $this->params = $params;

    if( isset( $urlData['path'] ) )
    {
      $tmp = explode( '.', $urlData['path'] );

      $this->service = ucfirst($tmp[0]);

      if( isset( $tmp[1] ) )
        $this->action = $tmp[1];
      else
        $this->action = 'default';
    }
    else
    {
      $this->service  = 'Help';
      $this->action   = 'default';
    }

  }//end public function __construct */

////////////////////////////////////////////////////////////////////////////////
// param methodes
////////////////////////////////////////////////////////////////////////////////


  /**
   * Funktion zum testen ob eine bestimmte Urlvariable existiert
   *
   * @param string Key Name der zu erfragende $_GET Variable
   * @return bool
   */
  public function paramExists( $key )
  {

    return isset( $this->params[$key] );

  } // end public function paramExists */

  /**
  * Daten einer bestimmten Urlvariable erfragen
  *
  * @param string $key
  * @param string $validator
  * @return string
  */
  public function param( $key = null, $validator = null )
  {

    return isset( $this->params[$key] )
      ? $this->params[$key]
      : null;

  }//end public function param */

 /**
  * Hinzufügen oder ersetzten einer Variable in der URL
  *
  * @param string $key Name des Urlkeys
  * @param string $data Die Daten für die Urlvar
  * @return bool
  */
  public function addParam( $key, $data = null  )
  {

    $this->params[$key] = $data;

  }//end public function addParam */

  /**
   * remove some variables from the url
   * @param string $key
   */
  public function removeParam( $key )
  {

    if( isset( $this->params[$key] ) )
      unset( $this->params[$key] );

  }//end public function removeParam */

/*//////////////////////////////////////////////////////////////////////////////
//
//////////////////////////////////////////////////////////////////////////////*/


  /**
   * Abfragen des Status einer POST Variable
   *
   * @param string Key Name der zu prüfenden Variable
   * @return bool
   */
  public function dataExists( $key , $subkey = null )
  {

    if( !is_null( $subkey ) )
    {
      return isset( $this->data[$key][$subkey] );
    }
    else
    {
      return isset( $this->data[$key] );
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

    if( !isset( $this->data[$key] ) || !is_array( $this->data[$key] ) )
      return array();

    $keys = array_keys( $this->data[$key] );

    $tmp = array();

    foreach( $keys as $key )
    {

      if( 'id_' == substr( $key , 0, 3 ) )
        $tmp[] = $key;
    }

    return $tmp;

  } // end public function dataSearchIds */

  /**
  * Auslesen einer Postvariable
  *
  * @param array/string[optional] Array mit Namen von Keys / Key Name der Variable
  * @return array
  */
  public function data( $key = null , $validator = null , $subkey = null , $message = null  )
  {

    $response = $this->getResponse();

    if( $validator )
    {
      $filter = $this->getValidator();
      $filter->clean(); // first clean the filter

      if( is_string($key) )
      {

        if( $subkey )
        {

          if( isset( $this->data[$key][$subkey] ) )
          {
            $data = $this->data[$key][$subkey];
          }
          else
          {
            return null;
          }

        }//end if $subkey
        else
        {

          if( isset( $this->data[$key] ) )
          {
            $data = $this->data[$key];
          }
          else
          {
            return null;
          }

        }

        $fMethod = 'add'.ucfirst($validator);

        if( is_array( $data ) )
        {
          // Clean all the same way
          // Good architecture :-)
          return $this->validateArray( $fMethod , $data );
        }
        else
        {
          // clean only one
          if( !$error = $filter->$fMethod( $key, $data ) )
          {
            return $filter->getData( $key );
          }
          else
          {
            $response->addError( ($message?$message:$error) ) ;
            return;
          }

        }

      }// end is_string($key)
      elseif( is_array( $key ) )
      {
        $data = array();

        if( is_array( $validator ) )
        {
          foreach( $key as $id )
          {
            $fMethod = 'add'.ucfirst($validator[$id] );

            if( isset($this->data[$id]) )
            {
              $filter->$fMethod( $this->data[$id], $id );
              $data[$id] = $filter->getData($id);
            }
            else
            {
              $data[$id] = null;
            }
          }
        }
        else
        {
          foreach( $key as $id )
          {
            $fMethod = 'add'.ucfirst($validator);

            if( isset($this->data[$id]) )
            {
              $filter->$fMethod( $this->data[$id], $id );
              $data[$id] = $filter->post($id);
            }
            else
            {
              $data[$id] = null;
            }
          }
        }

        return $data;

      }
    }//end if $validator
    else // else $validator
    {
      if( is_string($key) )
      {
        if($subkey)
        {
          return isset($this->data[$key][$subkey])
            ?$this->data[$key][$subkey]:null;
        }
        else
        {
          return isset($this->data[$key])
            ?$this->data[$key]:null;
        }
      }
      elseif( is_array($key) )
      {
        $data = array();

        foreach( $key as $id )
        {
          $data[$id] = isset( $this->data[$id] )? $this->data[$id] :null;
        }

        return $data;
      }
      elseif( is_null($key) )
      {
        return $this->data;
      }
      else
      {
        return null;
      }
    }

  } // end public function data */

  /**
   * remove some variables from the url
   *
   */
  public function removeData( $key , $subkey = null )
  {


    if( is_null( $subkey ) )
    {
      if( isset( $this->data[$key] ) )
      {
        unset( $this->data[$key] );
      }
    }
    else
    {
      if( isset( $this->data[$key][$subkey] ) )
      {
        unset( $this->data[$key][$subkey] );
      }
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

          if( !isset( $this->data[$subkey][$key] ) )
          {
            return true;
          }

          if( trim($this->data[$subkey][$key]) == '' )
          {
            return true;
          }

          return false;

        }

      }
      else
      {

        if( !isset( $this->data[$subkey][$keys] ) )
        {
          return true;
        }

        if( trim($this->data[$subkey][$keys]) == '' )
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

          if( !isset( $this->data[$key] ) )
            return true;

          if( trim($this->data[$key]) == '' )
            return true;

          return false;

        }

      }
      else
      {

        if( !isset( $this->data[$keys] ) )
          return true;

        if( trim($this->data[$keys]) == '' )
          return true;

        return false;

      }
    }

  } // end public function dataEmpty */



/*//////////////////////////////////////////////////////////////////////////////
// Cookie
//////////////////////////////////////////////////////////////////////////////*/

  /**
  * request if we have a cookie with this name
  *
  * @param string Key Name des zu testenden Cookies
  * @return bool
  */
  public function issetCookie( $key  )
  {
    return false;
  } // end public function issetCookie */

 /**
  * Abfragen einer bestimmten Postvariable
  *
  * @param string Key Name des angefragten Cookies
  * @return string
  */
  public function cookie( $key = null , $validator = null, $message = null )
  {
    return null;
  } // end public function cookie */

 /**
  * Request if a File Upload Exists
  *
  * @param string Key Name des zu testenden Cookies
  * @return bool
  */
  public function fileExists( $key )
  {
    return false;
  } // end public function fileExists */

 /**
  * Request if a File Upload Exists
  *
  * @param string Key Name des zu testenden Cookies
  * @param string Key Name des zu testenden Cookies
  * @return bool
  */
  public function file( $key = null, $type = null, $subkey = null, $message = null )
  {
    return null;

  } // end public function getUploadedFile */

  /**
  * request if we have a cookie with this name
  *
  * @param string Key Name des zu testenden Cookies
  * @return bool
  */
  public function serverExists( $key  )
  {

    return $this->request->serverExists( $key  );

  } // end public function serverExists */

  /**
  * Abfragen einer bestimmten Postvariable
  *
  * @param string Key Name des angefragten Cookies
  * @return string
  */
  public function server( $key = null , $validator = null, $message = null )
  {

    return $this->request->server( $key, $validator, $message  );

  } // end public function server */

  /**
  * request if we have a cookie with this name
  *
  * @param string Key Name des zu testenden Cookies
  * @return bool
  */
  public function envExists( $key  )
  {
    return $this->request->envExists( $key );
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

    return $this->request->env( $key, $validator, $message );

  } // end public function env */


////////////////////////////////////////////////////////////////////////////////
// Get Client Informations
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * Enter description here...
   * @return string
   */
  public function getBrowser()
  {
    return $this->request->getBrowser();
  }//end public function getBrowser */

  /**
   *
   * Enter description here...
   * @return string
   */
  public function getBrowserVersion()
  {
    return $this->request->getBrowserVersion();

  }//end public function getBrowserVersion */

  /**
   *
   * Enter description here...
   * @return string
   */
  public function getPlatform()
  {

    return $this->request->getPlatform();

  }//end public function getPlatform */

  /**
   *
   * Enter description here...
   * @return string
   */
  public function getUseragent()
  {

    return $this->request->getUseragent();

  }//end public function getUseragent */


  /**
   *
   * Enter description here...
   * @return string
   */
  public function getClientIp()
  {

    return $this->request->getClientIp();

  }//end public function getClientIp */

  /**
   *
   * Enter description here...
   * @return string
   */
  public function getEncoding()
  {
    return $this->request->getEncoding();
  }//end public function getEncoding */

  /**
   *
   * Enter description here...
   * @return string
   */
  public function getClientLanguage()
  {

    return $this->request->getClientLanguage();

  }//end public function getClientLanguage */

  /**
   *
   * Enter description here...
   * @return string
   */
  public function getCharset()
  {

    return $this->request->getCharset();

  }//end public function getClientIp */


  /**
   *
   * Enter description here...
   * @return string
   */
  public function getClientRefer()
  {

    return $this->request->getClientRefer();

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

    return $this->request->method( $requested );

  }//end public function method */

  /**
   * get the request method
   *
   * @return string
   */
  public function inMethod( $methodes )
  {

    return $this->request->inMethod( $methodes );

  }//end public function inMethod */

  /**
   * @return boolean
   */
  public function isAjax()
  {

    return $this->request->isAjax(  );

  }//end public function isAjax */


  /**
   * @return boolean
   */
  public function getResource()
  {
    return $this->request->getResource(  );

  }//end public function getResource */


}// end class RequestSubCli


