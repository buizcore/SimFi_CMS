<?php



/**
 * 
 * @subpackage web_expert
 *
 */
class RequestSubHttp
{

  /**
   * Der Vate request Knoten
   * @var RequestHttp
   */
  protected $request = null;

  /**
   * post data array
   * @var array
   */
  protected $data   = array();

  /**
   * files data array
   * @var array
   */
  protected $files  = array();

  /**
   * @var Validator
   */
  protected $validator  = null;

  /**
   * @var Validator
   */
  protected $messages  = null;

////////////////////////////////////////////////////////////////////////////////
// Getter & Setter Methodes
////////////////////////////////////////////////////////////////////////////////


  /**
   * @param Validator $validator
   */
  public function setValidator( $validator )
  {

    $this->validator = $validator;

  }//end public function setValidator */

  /**
   * @return Validator
   */
  public function getValidator()
  {
    // set default orm
    if( !$this->validator )
      $this->validator = Validator::getActive();

    return $this->validator;

  }//end public function getValidator */

  /**
   * @param LibRequestPhp $request
   * @param array $data
   * @param array $files
   */
  public function __construct( $request, $data, $files, $messages )
  {

    $this->request = $request;
    $this->data    = $data;
    $this->files   = $files;
    $this->messages = $messages;

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

    return $this->request->paramExists( $key );

  } // end public function paramExists */

  /**
  * Daten einer bestimmten Urlvariable erfragen
  *
  * @param string Key Name der zu erfragende $_GET Variable
  * @return string
  */
  public function param( $key = null , $validator = null , $message = null )
  {

    return $this->request->param( $key, $validator, $message  );

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

    $this->request->addParam( $key, $data );

  }//end public function addParam */

  /**
   * remove some variables from the url
   * @param string $key
   */
  public function removeParam( $key )
  {

    $this->request->removeParam( $key  );

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
            $this->messages->addError( ($message?$message: $key.':'.$error) ) ;
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

    /*
    $subkey
      ? isset($this->data[$key][$subkey])
        ? unset($this->data[$key][$subkey])
        : null

      : isset($this->data[$key])
        ? unset($this->data[$key])
        : null;
    */

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
//
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * Enter description here...
   *
   * @param unknown_type $fMethod
   * @param unknown_type $data
   * @return unknown
   */
  protected function validateArray($fMethod , $data )
  {

    $filter = $this->getValidator();

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
    return $this->request->issetCookie( $key  );
  } // end public function issetCookie */

 /**
  * Abfragen einer bestimmten Postvariable
  *
  * @param string Key Name des angefragten Cookies
  * @return string
  */
  public function cookie( $key = null , $validator = null, $message = null )
  {
    return $this->request->issetCookie( $key, $validator, $message );
  } // end public function cookie */

 /**
  * Request if a File Upload Exists
  *
  * @param string Key Name des zu testenden Cookies
  * @return bool
  */
  public function fileExists( $key )
  {

    if( isset( $this->files[$key] ) )
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
  * @param string Key Name des zu testenden Cookies
  * @param string Key Name des zu testenden Cookies
  * @return bool
  */
  public function file( $key = null, $type = null, $subkey = null, $message = null )
  {
    if( is_null($key) )
    {
      return $this->files;
    }

    $filter = $this->getValidator();
    $filter->clean(); // first clean the filter

    if( $subkey )
    {

      // asume this was just an empty file
      if( !isset($this->files[$subkey]) || '' == trim($this->files[$subkey]['name'][$key]) )
      {
        $data = null;
      }
      else
      {
        $data = array();
        $data['name']     = $this->files[$subkey]['name'][$key];
        $data['type']     = $this->files[$subkey]['type'][$key];
        $data['tmp_name'] = $this->files[$subkey]['tmp_name'][$key];
        $data['error']    = $this->files[$subkey]['error'][$key];
        $data['size']     = $this->files[$subkey]['size'][$key];
      }

    }
    else
    {
      // asume this was just an empty file
      if( !isset($this->files[$key]) || '' == trim($this->files[$key]['name']) )
      {
        $data = null;
      }
      else
      {
        $data = $this->files[$key];
      }

    }

    if( !$data )
      return null;

    if( $type )
    {
      $classname = 'LibUpload'.SParserString::subToCamelCase($type);

      if(!Webfrap::classLoadable($classname))
        throw new LibFlow_Exception('Requested nonexisting upload type: '.$classname );

      return new $classname($data,$key);

    }
    else
    {
      return new LibUploadFile($data);
    }

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
// Form input
////////////////////////////////////////////////////////////////////////////////

  /** method for validating Formdata
   * if an error is found an message will be send to system, if you want to find
   * out if the test failed ask the system if there are any error messages
   *
   *
   * @param array $values
   * @param array $messages
   * @param string $subkey
   * @return ObjValidatorUserinputAbstract
   *
   */
  public function checkFormInput( $values , $messages, $subkey = null  )
  {

    // get Validator from Factory
    $filter = $this->getValidator();
    $filter->clean();

    if( $subkey )
    {// check if we have a subkey
      foreach( $values as $key => $value )
      {

        $method = 'add'.ucfirst($value[0]) ;

        if( Validator::FILE == ucfirst($value[0]) )
        {
          if( isset($this->files[$subkey]) )
          {
            // asume this was just an empty file
            if( '' == trim($this->files[$subkey]['name'][$key])  )
            {
              $data = null;
            }
            else
            {
              $data = array();
              $data['name']     = $this->files[$subkey]['name'][$key];
              $data['type']     = $this->files[$subkey]['type'][$key];
              $data['tmp_name'] = $this->files[$subkey]['tmp_name'][$key];
              $data['error']    = $this->files[$subkey]['error'][$key];
              $data['size']     = $this->files[$subkey]['size'][$key];
            }
          }
          else
          {
            $data = null;
          }
        }
        else
        {

          if( isset($this->data[$subkey][$key]) )
          {
            $data = $this->data[$subkey][$key];
          }
          else
          {
            $data = null;
          }
        }

        if( $error = $filter->$method( $key , $data, $value[1] , $value[2] , $value[3] ) )
        {
          if( isset( $messages[$key][$error] ) )
          {
            $filter->addErrorMessage( $messages[$key][$error] );
          }
          elseif( isset( $messages[$key]['default'] ) )
          {
            $filter->addErrorMessage( $messages[$key]['default'] );
          }
          else
          {
            $filter->addErrorMessage( 'Wrong data for '.$key  );
          }

        }

      }

    }
    else
    {// we have no subkey geht direct

      foreach( $values as $key => $value )
      {

        $method = 'add'.$value[0] ;

        if( Validator::FILE == ucfirst($value[0]) )
        {
          if( isset($this->files[$key]) )
          {
            $data = $this->files[$key];
          }
          else
          {
            $data = null;
          }
        }
        else
        {
          if( isset($this->data[$key]) )
          {
            $data = $this->data[$key];
          }
          else
          {
            $data = null;
          }
        }

        if( $error = $filter->$method( $key , $data, $value[1] , $value[2] , $value[3] ) )
        {
          if( isset( $messages[$key][$error] ) )
          {
            $filter->addErrorMessage( $messages[$key][$error] );
          }
          elseif( isset( $messages[$key]['default'] ) )
          {
            $filter->addErrorMessage( $messages[$key]['default'] );
          }
          else
          {
            $filter->addErrorMessage( 'Wrong data for '.$key  );
          }

        }

      }

    }

    return $filter;

  }//end public function checkFormInput */

  /** method for validating Formdata
   * if an error is found an message will be send to system, if you want to find
   * out if the test failed ask the system if there are any error messages
   *
   *
   * @param array $values
   * @param array $messages
   * @param string $subkey
   * @return Validator
   *
   */
  public function checkSearchInput( $values , $messages, $subkey = null )
  {

    // get Validator from Factory
    $filter = $this->getValidator();
    $filter->clean();

    $validator = null;

    if( $subkey )
    {// check if we have a subkey

      foreach( $values as $key => $value )
      {
        $method = 'add'.$value[0] ;

        if( isset($this->data[$subkey][$key]) )
          $data = $this->data[$subkey][$key];
        else
          $data = null;

        if( is_array($data) )
        {

          if(!$validator)
            $validator = new LibValidatorBase();

          $checkMethod = 'check'.ucfirst($value[0]);

          $filtered = array();

          foreach( $data as $dataValue )
          {
            if( $validator->$checkMethod( $dataValue, false , $value[2] , $value[3] ) )
            {
              $filtered[] = $validator->sanitized;
              $validator->clean();
            }
          }

          if( $filtered )
            $filter->appendCleanData( $key, $filtered );

        }
        else
        {
          if( $error = $filter->$method( $key , $data, false , $value[2] , $value[3] ) )
          {
            if( isset( $messages[$key][$error] ) )
            {
              $this->messages->addError( $messages[$key][$error] );
            }
            elseif( isset( $messages[$key]['default'] ) )
            {
              $this->messages->addError( $messages[$key]['default'] );
            }
            else
            {
              $this->messages->addError( 'Wrong data for '.$key  );
            }
          }
        }


      }
    }
    else
    {// we have no subkey geht direct

      foreach( $values as $key => $value )
      {

        $method = 'add'.$value[0] ;

        if( isset($this->data[$key]) )
          $data = $this->data[$key];
        else
          continue;

        if( $error = $filter->$method( $key , $data, false , $value[2] , $value[3] ) )
        {

          if( isset( $messages[$key][$error] ) )
          {
            $this->messages->addError( $messages[$key][$error] );
          }
          elseif( isset( $messages[$key]['default'] ) )
          {
            $this->messages->addError( $messages[$key]['default'] );
          }
          else
          {
            $this->messages->addError( 'Wrong data for '.$key  );
          }

        }

      }

    }

    if( Log::$levelTrace )
      Debug::console( 'checkSearchInput filter data',$filter->data );

    return $filter;

  }//end public function checkSearchInput */



  /**
   * @param string $key
   * @param string $subkey
   */
  public function checkMultiIds( $key , $subkey = null )
  {

    $ids    = array();

    if($subkey)
    {
      foreach( $this->data[$key][$subkey] as $val )
      {
        if( is_numeric($val) )
          $ids[] = $val;
      }
    }
    else
    {
      foreach( $this->data[$key] as $val )
      {
        if( is_numeric($val) )
          $ids[] = $val;
      }
    }

    return $ids;

  }//end public function checkMultiIds */


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


}// end class LibRequestSubrequest


