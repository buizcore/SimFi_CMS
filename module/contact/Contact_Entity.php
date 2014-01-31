<?php


/**
 * @package com.BuizCore
 * @subpackage SimFi
 *
 * @property string salutation
 * @property string surname
 * @property string lastname
 * @property string company
 * @property string street
 * @property string street_num
 * @property string city
 * @property string postalcode
 * @property string country
 */
class Contact_Entity extends DbEntity
{

  /**
   * @var string name der tabelle
   */
  public static $table = 'simfi_contact';

  /**
   * @var string metadaten für die datenbank felder
   */
  public static $cols = array(
    'salutation' => array(
      Db::TYPE => 'text',
      Db::SIZE => 1,
      Db::REQUIRED => true,
      Db::VALIDATOR => Validator::TEXT
    ),
    'surname' => array(
      Db::TYPE => 'text',
      Db::SIZE => 250,
      Db::REQUIRED => true,
      Db::VALIDATOR => Validator::TEXT
    ),
    'lastname' => array(
      Db::TYPE => 'text',
      Db::SIZE => 250,
      Db::REQUIRED => true,
      Db::VALIDATOR => Validator::TEXT
    ),
    'company' => array(
      Db::TYPE => 'text',
      Db::SIZE => 250,
      Db::VALIDATOR => Validator::TEXT
    ),
    'street' => array(
      Db::TYPE => 'text',
      Db::SIZE => 250,
      Db::VALIDATOR => Validator::TEXT
    ),
    'street_num' => array(
      Db::TYPE => 'text',
      Db::SIZE => 5,
      Db::VALIDATOR => Validator::TEXT
    ),
    'city' => array(
      Db::TYPE => 'text',
      Db::SIZE => 250,
      Db::VALIDATOR => Validator::TEXT
    ),
    'postalcode' => array(
      Db::TYPE => 'text',
      Db::SIZE => 10,
      Db::VALIDATOR => Validator::TEXT
    ),
    'country' => array(
      Db::TYPE => 'text',
      Db::SIZE => 250,
      Db::VALIDATOR => Validator::TEXT
    ),
    'telefon' => array(
      Db::TYPE => 'text',
      Db::SIZE => 30,
      Db::VALIDATOR => Validator::TEXT
    ),
    'email' => array(
      Db::TYPE => 'text',
      Db::SIZE => 250,
      Db::REQUIRED => true,
      Db::UNIQUE => true,
      Db::VALIDATOR => Validator::EMAIL
    ),
    'comment' => array(
      Db::TYPE => 'text',
      Db::VALIDATOR => Validator::TEXT
    ),
    'send_copy' => array(
      Db::TYPE => 'boolean',
      Db::VALIDATOR => Validator::BOOLEAN
    ),

  );

}//end class User_Entity */
