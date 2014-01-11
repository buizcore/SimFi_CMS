<?php


/**
 * 
 * @subpackage web_expert.cms
 */
class Contact_Request extends RequestTransaction
{

  /**
   * @var array
   */
  public $saveFields = array(
    'contact' => array(
      'salutation',
      'surname',
      'lastname',
      'company',
      'street',
      'street_num',
      'postalcode',
      'city',
      'country',
      'telefon',
      'email',
      'comment',
      'send_copy'
    )
  );

}//end class Contact_Request */
