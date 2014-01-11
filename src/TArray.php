<?php



/**
 * Ein Array Objekt für Simple Daten
 * @package WebFrap
 * @subpackage WebExpert
 */
class TArray implements Iterator, Countable
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var array
   */
  protected $pool = array();

////////////////////////////////////////////////////////////////////////////////
// Magic Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * Standard Konstruktor
   * Nimmt beliebig viele Elemente oder einen einzigen Array
   */
  public function __construct( )
  {

    if( $anz = func_num_args() )
    {
      if( $anz == 1 and is_array(func_get_arg(0)) )
      {
        $this->pool = func_get_arg(0);
      }
      else
      {
        // hier kommt auf jeden fall ein Array
        $this->pool = func_get_args();
      }
    }

  }//end public function __construct( )

  /**
   * Zugriff Auf die Elemente per magic set
   * @param string $key
   * @param mixed $value
   */
  public function set( $key , $value )
  {
    $this->pool[$key] = $value;
  }// end of public function __set */

  /**
   * Zugriff Auf die Elemente per magic set
   * @param string $key
   * @param mixed $value
   */
  public function __set( $key , $value )
  {
    $this->pool[$key] = $value;
  }// end of public function __set */

  /**
   * Zugriff Auf die Elemente per magic get
   *
   * @param string $key
   * @return mixed
   */
  public function __get( $key )
  {
    return isset($this->pool[$key])?$this->pool[$key]:null;
  }// end of public function __get */

  public function htmlSafe($key)
  {
    if (!isset($this->pool[$key]) )
      return '';

    return htmlentities($this->pool[$key]);
  }

////////////////////////////////////////////////////////////////////////////////
// Interface: ArrayAccess
////////////////////////////////////////////////////////////////////////////////

  /**
   * @see ArrayAccess:offsetSet
   */
  public function offsetSet($offset, $value)
  {
    $this->pool[$offset] = $value;
  }//end public function offsetSet */

  /**
   * @see ArrayAccess:offsetGet
   */
  public function offsetGet($offset)
  {
    return $this->pool[$offset];
  }//end public function offsetGet */

  /**
   * @see ArrayAccess:offsetUnset
   */
  public function offsetUnset($offset)
  {
    unset($this->pool[$offset]);
  }//end public function offsetUnset */

  /**
   * @see ArrayAccess:offsetExists
   */
  public function offsetExists($offset)
  {
    return isset($this->pool[$offset])?true:false;
  }//end public function offsetExists */

////////////////////////////////////////////////////////////////////////////////
// Interface: Iterator
////////////////////////////////////////////////////////////////////////////////

  /**
   * (non-PHPdoc)
   * @see Iterator::current()
   */
  public function current ()
  {
    return current($this->pool);
  }//end public function current */

  /**
   * (non-PHPdoc)
   * @see Iterator::key()
   */
  public function key ()
  {
    return key($this->pool);
  }//end public function key */

  /**
   * (non-PHPdoc)
   * @see Iterator::next()
   */
  public function next ()
  {
    return next($this->pool);
  }//end public function next */

  /**
   * (non-PHPdoc)
   * @see Iterator::rewind()
   */
  public function rewind ()
  {
    reset($this->pool);
  }//end public function rewind */

  /**
   * (non-PHPdoc)
   * @see Iterator::valid()
   */
  public function valid ()
  {
    return current($this->pool)? true:false;
  }//end public function valid */

////////////////////////////////////////////////////////////////////////////////
// Interface: Countable
////////////////////////////////////////////////////////////////////////////////

  /**
   * (non-PHPdoc)
   * @see Countable::count()
   */
  public function count()
  {
    return count($this->pool);
  }//end public function count */

}//end class TArray
