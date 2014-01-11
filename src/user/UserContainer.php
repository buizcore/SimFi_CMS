<?php



/**
 * @package com.BuizCore
 * @subpackage SimFi
 */
class UserContainer
  implements UserDataInf
{
////////////////////////////////////////////////////////////////////////////////
// Input Daten
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * @var string
   */
  public $name = null;
  
  /**
   * @var string
   */
  public $firstname = null;  
  
  /**
   * @var string
   */
  public $lastname = null;
  
  /**
   * @var string
   */
  public $password = null;
  
  /**
   * @var string
   */
  public $email = null;
  
  /**
   * @var string
   */
  public $profile = null;

  /**
   * @var string
   */
  public $level = null;
  
////////////////////////////////////////////////////////////////////////////////
// Metadata
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * @var array
   */
  public $levels = array
  (
    'public_edit'    => 0,
    'public_access'  => 10,
    'user'           => 20,
    'ident'          => 30,
    'employee'       => 40,
    'superior'       => 50,
    'l4_manager'     => 60,
    'l3_manager'     => 70,
    'l2_manager'     => 80,
    'l1_manager'     => 90,
    'system'         => 100,
    1     => 0,
    2     => 10,
    3     => 20,
    4     => 30,
    5     => 40,
    6     => 50,
    7     => 60,
    8     => 70,
    9     => 80,
    10    => 90,
    11    => 100,
  );
  
  /**
   * @var array
   */
  public $profiles = array
  (
    'developer' => 'developer',
    'admin' => 'admin',
    'user' => 'user',
    1 => 'developer',
    2 => 'admin',
    3 => 'user',
  );
  
////////////////////////////////////////////////////////////////////////////////
// 
////////////////////////////////////////////////////////////////////////////////

  /**
   * 
   */
  public function read()
  {
    
    Console::out( 'Username:' );
    $this->username = Console::in( );
    
    Console::out( 'Firstname:' );
    $this->firstname = Console::in( );
    
    Console::out( 'Lastname:' );
    $this->lastname = Console::in( );
    
    $this->readPassword();
    
    Console::out( 'E-Mail:' );
    $this->email = Console::in( );
    
    $this->readProfile();
    $this->readLevel();
    
  }//end public function read */
  
  /**
   * 
   */
  public function readPassword()
  {
    
    Console::out( 'Password:' );
    $password = Console::secretIn( );
    
    Console::out( 'Repeat Password:' );
    $password2 = Console::secretIn( );
    
    if( $password != $password2 )
    {
      Console::out( 'Sorry password where not equal' );
      $this->readPassword();
    }
    else 
    {
      $this->password = $password;
    }

  }//end public function readPassword */
  
  /**
   * 
   */
  public function readLevel()
  {
    
    Console::outln( 'Level:' );
    Console::outln( '- public_edit' );
    Console::outln( '- public_access' );
    Console::outln( '- user' );
    Console::outln( '- ident' );
    Console::outln( '- employee' );
    Console::outln( '- superior' );
    Console::outln( '- l4_manager' );
    Console::outln( '- l3_manager' );
    Console::outln( '- l2_manager' );
    Console::outln( '- l1_manager' );
    Console::outln( '- system' );
    
    $level = trim(Console::in( ));
    
    if( !isset($this->levels[$level]) )
    {
      Console::outln( "Falsches Level angegeben {$level}" );
      $this->readLevel();
    }
    else 
    {
      $this->level = $this->levels[$level];
    }

  }//end public function readLevel */
  
  /**
   * 
   */
  public function readProfile()
  {
    
    Console::outln( 'Choose Profile:' );
    Console::outln( '- developer' );
    Console::outln( '- admin' );
    Console::outln( '- user' );
    
    $profile = trim(Console::in( ));
    
    if( in_array( $profile, $this->profiles ) )
    {
      $this->profile = $profile;
    }
    else 
    {
      Console::outln( "Wrong profile {$profile}" );
      $this->readProfile();
    }
    
  }//end public function readProfile */
  
  /**
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }//end public function getName */
  
  /**
   * @return string
   */
  public function getPasswd()
  {
    return $this->password;
  }//end public function getPasswd */

  /**
   * @return string
   */
  public function getFirstname()
  {
    return $this->firstname;
  }//end public function getFirstname */
  
  /**
   * @return string
   */
  public function getLastname()
  {
    return $this->lastname;
  }//end public function getLastname */
  
  /**
   * @return string
   */
  public function getLevel()
  {
    return $this->level;
  }//end public function getLevel */
  
  /**
   * @return string
   */
  public function getProfile()
  {
    return $this->profile;
  }//end public function getProfile */
  
  /**
   * @return string
   */
  public function getEmail()
  {
    return $this->email;
  }//end public function getEmail */
  
}//end class UserContainer */
