<?php


/**
 * @package com.BuizCore
 * @subpackage SimFi
 */
class FormatNumber
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var FormatNumber
   */
  protected static $instance  = null;

  /**
   * @var float
   */
  protected $numericEnglish    = null;

  /**
   * @var string
   */
  protected $numericLanguage   = null;

  /**
   * seperator from full to broken numbers 20,44
   * @var string
   */
  protected $separatorDec      = ',';

  /**
   * visual seperator to make numbers better readbale 200.000.394
   *
   * @var string
   */
  protected $separatorTh       = '.';

  /**
   * precission
   * @var int
   */
  protected $size              =  2;

  /**
   * @var boolean
   */
  protected $negativ           = false;

////////////////////////////////////////////////////////////////////////////////
// Magic
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $i18n
   * @param int $precision
   */
  public function __construct( $i18n = null, $precision = 2 )
  {

    if( !$i18n )
      $i18n = I18n::getActive();

    $this->size         = $precision;
    $this->separatorDec = $i18n->numberDec;
    $this->separatorTh  = $i18n->numberMil;

  }//end public function __construct */

  /**
   * @return string
   */
  public function __toString()
  {
    return $this->formatToEnglish();

  }//end public function __toString

////////////////////////////////////////////////////////////////////////////////
// Half Singleton
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return FormatNumber
   */
  public static function getActive()
  {

    if( is_null( self::$instance) )
    {
      self::$instance = new FormatNumber();
    }

    return self::$instance;

  }//end public static function getActive */

  /**
   * 
   * @param string $separatorDec
   * @param string $separatorTh
   * @param int $size
   * 
   * @return LibFormatterNumeric
   */
  public static function langFormatter( $separatorDec = ',', $separatorTh = '.', $size = 2 )
  {

    $obj = new FormatNumber();
    $obj->setFormat( $separatorDec, $separatorTh, $size );

    return $obj;

  }//end public static function getLang

////////////////////////////////////////////////////////////////////////////////
// Getter and Setter
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $separatorDec
   * @param string $separatorTh
   * @param string $size
   */
  public function setFormat( $separatorDec = ',', $separatorTh = '.', $size = 2 )
  {
    $this->separatorDec = $separatorDec;
    $this->separatorTh  = $separatorTh;
    $this->size         = $size;
  }//end public function setFormat */

  /**
   * @param string $numeric
   */
  public function setNumericLanguage( $numeric )
  {

    $this->negativ = false;

    $numeric = trim($numeric);

    if( '-' == $numeric[0] )
      $this->negativ = true;

    $this->numericLanguage = $numeric;

    $catchablePatterns = array('(?:[0-9]*)(?:e[+-]?[0-9]+)?');
    $regex = '/(' .implode(')|(', $catchablePatterns) . ')/i';
    $flags = PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE ;
    $rawMatches = preg_split( $regex, trim($numeric), -1, $flags);

    if( in_array( $this->separatorDec ,  $rawMatches )  )
    {

      $num = '';
      $end = array_pop( $rawMatches );

      if( !ctype_digit($end) )
      {
        $end = '00';
      }

      $num = '';

      foreach( $rawMatches as $match )
      {

        if( ctype_digit($match) )
          $num .= $match;

        $this->numericEnglish = (float)(($this->negativ?'-':'').$num.'.'.$end);

      }

    }
    else
    {
      $num = '';

      foreach( $rawMatches as $match )
      {

        if( ctype_digit($match) )
          $num .= $match;

        $this->numericEnglish = (float)(($this->negativ?'-':'').$num.'.00');

      }
    }


  }//end public function setNumericLanguage */

  /**
   * @param string $english
   */
  public function setNumericEnglish( $english )
  {
    $this->numericEnglish = $english;
  }//end public function setnumericEnglish */


////////////////////////////////////////////////////////////////////////////////
// Logic
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $numeric
   */
  public function formatToEnglish( $numeric = null )
  {

    if( !is_null($numeric)  )
      $this->setNumericLanguage($numeric);

    return $this->numericEnglish;

  }//end public function formatToEnglish

  /**
   * @param string $numeric
   */
  public function formatToLanguage( $numeric = null )
  {

    if( is_null( $numeric ) )
     $numeric = $this->numericEnglish;

    return number_format
    (
      $this->numericEnglish ,
      $this->size,
      $this->separatorDec ,
      $this->separatorTh
    );

  }//end public function formatToLanguage

} // end of FormatNumber

