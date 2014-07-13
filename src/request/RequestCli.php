<?php


/**
 * @package com.BuizCore
 * @subpackage SimFi
 *
 */
class RequestCli implements IsARequest
{
/*//////////////////////////////////////////////////////////////////////////////
// Attributes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @var string
   */
  public $service = null;

  /**
   * @var string
   */
  public $action = 'default';

  /**
   * @var array
   */
  protected $params = array();

  /**
   * @var array
   */
  protected $flags = array();

  /**
   * @param array $args
   */
  public function __construct( $args )
  {

    if( 1 == count($args) )
      return;

    $fileName = array_shift($args);
    $actionArc = array_shift($args);
    
    $tmp = explode('.', $actionArc);
    
    $this->service = $tmp[0];
    
    if (isset($tmp[1])) {
        $this->action = $tmp[1];
    }

    foreach( $args as $pos => $argument ) {

      if( !$pos )
        continue;

      if( strpos( $argument, '=' ) ) {
        
        $tmp = explode( '=', $argument );
        $this->params[$tmp[0]] = $tmp[1];
      
      } else if( '-' == $argument[0] ) {

          $this->flags[str_replace( '-', '', $argument )] = true;
      
      } else {
          
        if( !$this->service )
          $this->service = FormatString::subToCamelCase($argument) ;
        else
          $this->action = $argument;
      }

    }

  }//end public function __construct */

  /**
   * @param string $key
   */
  public function flag( $key )
  {
    return isset($this->flags[$key]);
  }//end public function flag */

  /**
   * @param string $key
   * @return string
   */
  public function param( $key, $validator = null )
  {

    return isset($this->params[$key])
      ?$this->params[$key]
      :null;

  }//end public function param */

  /**
   *
   * /
  public function init()
  {

    if( 2 < count( $_SERVER['argv']) )
    {
      $parsed = null;
      parse_str( $_SERVER['argv'][2], $parsed );

      $this->params = $parsed;
    }

    if( isset( $_SERVER['argv'][1] ) )
    {

      $tmp = explode( '.', $_SERVER['argv'][1] );

      $this->action = $tmp[0];

      if( isset( $tmp[1] ) )
        $this->action = $tmp[1];
      else
        $this->action = 'default';

    }

  }//end public function init */

/*//////////////////////////////////////////////////////////////////////////////
// read
//////////////////////////////////////////////////////////////////////////////*/

  /**
   *
   */
  public function read()
  {
    return fgets(STDIN);
  }//end public function read */

}// end class RequestCli

