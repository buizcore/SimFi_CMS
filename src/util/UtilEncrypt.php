<?php


/**
*
*/
class UtilEncrypt
{


  private $ivs = null;
  private $iv = null;
  private $algo = MCRYPT_RIJNDAEL_256;
  private $mode = MCRYPT_MODE_ECB;

  public function __construct()
  {

    $this->ivs = mcrypt_get_iv_size( $this->algo, $this->mode );
    $this->iv = mcrypt_create_iv( $this->ivs );

  }

  /**
  *
  */
  public function encrypt( $text, $key )
  {
    $data = mcrypt_encrypt( $this->algo, $key, $text, $this->mode, $this->iv );
    return base64_encode( $data );
  }

  /**
  *
  */
  public function decrypt( $text, $key )
  {
    $data = base64_decode( $text );
    return mcrypt_decrypt( $this->algo, $key, $data, $this->mode, $this->iv );
  }

} // end LibEncrypt
