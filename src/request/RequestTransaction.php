<?php


/**
 * 
 * @subpackage web_expert
 *
 */
class RequestTransaction
{
/*//////////////////////////////////////////////////////////////////////////////
// Attributes
//////////////////////////////////////////////////////////////////////////////*/


  /**
   * Erkannte Fehler
   * @var array
   */
  public $errors = array();

  /**
   * @var Db_Connection
   */
  public $db = null;

  /**
   * @var array
   */
  public $saveFields = array();

  /**
   * @var array
   */
  public $multiSaveFields = array();

  /**
   * request data
   * @var array
   */
  public $entities = array();

  /**
   * request data
   * @var array
   */
  public $entityList = array();
  
  /**
   * Liste mit den Typen
   * @var array
   */
  public $fieldTypes = array();

  /**
   * @param string $error
   */
  public function addError($error)
  {
    $this->errors[] = $error;
  }

  /**
   * @param RequestHttp $request
   * @param [Entity] $entities
   */
  public function handleRequest( $request, $entities )
  {
    $this->handleSaveRequest( $request, $entities );
  }

  /**
   * @param RequestHttp $request
   * @param [Entity] $entities
   */
  public function handleSaveRequest($request, $entities)
  {

    $this->entities = $entities;

    foreach( $this->saveFields as $key => $fields ) {

      $subRqt = $request->getSubRequest($key, $this);
      $ent    = $entities[$key];

      foreach( $fields as $field ) {

        $ent->{$field} = $subRqt->data( $field, $ent->getValidator($field) );
      }

    }

  }//end public function handleSaveRequest */

  /**
   * @param string $value
   * @param string $validator
   */
  public function validate( $value, $validator )
  {
  
  
  }//end public function validate */
  
  /**
   * @param RequestHttp $request
   * @param [Entity] $entities
   */
  public function handleSearchRequest( $request )
  {


  }//end public function handleSearchRequest */

}// end class RequestTransaction

