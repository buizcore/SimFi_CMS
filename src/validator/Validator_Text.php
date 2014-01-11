<?php


  
/**
 * Validator für Text
 * @package WebFrap
 * @subpackage WebExpert
 */
class Validator_Text
  implements IsAValidator
{
  
  /**
   * @var string
   */
  const PLAIN = 'Validator_Text::plain';
  
  /**
   * @var string
   */
  const CKEY = 'Validator_Text::ckey';
  
  /**
   * @var string
   */
  const CNAME = 'Validator_Text::cname';
  
  /**
   * @var string
   */
  const SEARCH = 'Validator_Text::search';
  
  
  /* (non-PHPdoc)
   * @see IsaValidator::santisize()
   */
  public function santisize( $value, $db = null, $flags = null )
  {

    // TODO Auto-generated method stub
    
  }//end public function santisize */

  /* (non-PHPdoc)
   * @see IsaValidator::santisizeToContainer()
   */
  public function santisizeToContainer( $value, $key, $container, $db = null, $flags = null )
  {

    // TODO Auto-generated method stub
    
  }//end public function santisizeToContainer */

  /* (non-PHPdoc)
 * @see IsaValidator::validate()
 */
  public function validate( $value, $db = null, $flags = null )
  {

    // TODO Auto-generated method stub
    
  }//end public function validate */

  /* (non-PHPdoc)
   * @see IsaValidator::validateToContainer()
   */
  public function validateToContainer( $value, $key, $container, $db = null, $flags = null )
  {

    // TODO Auto-generated method stub
    
  }//end public function validateToContainer */


  
  
}//end class Validator_Text */

