<?php


/*
* Mail Header:
*
* To                                      (X)
* Subject                                 (X)
* User-Agent                              (X)
* MIME-Version                            (X)
* Content-Type                            (X)
* From                                    (X)
* Cc
* Bcc
* X-Priority                              (X)
* Importance                              (X)
* Content-Class
* Content-Transfer-Encoding               (X)
* Content-Disposition                     (X)
* Content-Description (bei Anhaengen)     (X)
* Reply-To                                (X)
* Return-Path                             (X)
*
* Received
* Delivered-To
* Message-ID
*
*/

/**
 * @package com.BuizCore
 * @subpackage SimFi
 *
 *
 */
class MessageMail
{
/*//////////////////////////////////////////////////////////////////////////////
// Attributes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * Das New Line Format das in den Messages verwendet wird   *
   */
  const NL = "\n";

  /**
   * Das New Line Format das beim versenden verwendet wird   *
   */
  const NLB = "\n";

  /**
   * @var string
   */
  protected $address  = null;

  /**
   * the mail Subject
   *
   * @var string
   */
  protected $subject  = null;

  /**
   * Plain Text
   *
   * @var string
   */
  protected $plainText  = null;

  /**
   * html text
   *
   * @var string
   */
  protected $htmlText   = null;

  /**
   * list of files to attach
   *
   * @var array<string>
   */
  protected $attachment = array();

  /**
   * list of files to attach
   *
   * @var array<string>
   */
  protected $embedded = array();

/*//////////////////////////////////////////////////////////////////////////////
// Header Attributes
//////////////////////////////////////////////////////////////////////////////*/

   /**
   * Der mime Type der Message   *
   * @var string
   */
  protected $mimeType   = '1.0';

  /**
   * Die Absender Addresse
   * @var string
   */
  protected $sender   = null;

  /**
   * @var string
   */
  protected $replyTo  = null;

    /**
   * Enter description here...
   *
   * @var array
   */
  protected $bbc   = array();

  /**
   * Enter description here...
   *
   * @var array
   */
  protected $cc   = array();

  /**
   * Enter description here...
   *
   * @var string
   */
  protected $contentType  = 'text/html';

  /**
   * Mail Charset
   *
   * @var string
   */
  protected $charset = 'ISO-8859-1';

  /**
   * set the x Priority
   *
   * @var string
   */
  protected $xPriority = '3 (Normal)';

  /**
   * the return Path for the Mail
   *
   * @var string
   */
  protected $returnPath = null;

  /**
   * the importance of the mail
   *
   * <ul>
   * <li>high</li>
   * <li>normal</li>
   * <li>low</li>
   * </ul>
   *
   * @var string
   */
  protected $importance = 'normal';

  /**
   *
   * Enter description here ...
   * @var LibTemplateMail
   */
  public $view = null;

  /**
   * Message Logger..
   * @var LibMessageLogger
   */
  protected $logger = null;

/*//////////////////////////////////////////////////////////////////////////////
// Getter and Setter
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @param String $address
   * @param String $sender
   * @param LibMessageLogger $logger
   */
  public function __construct
  (
    $address = null ,
    $sender = null,
    $logger = null
  ) {

    if ($address) {
      $this->address = $this->encode($address);
    }

    $conf = Conf::getActive();

    if ($sender) {
      $this->sender = $this->encode($sender);
    } elseif ($conf->def_mail_sender) {
      $this->sender = $conf->def_mail_sender;
    } else {
      $this->sender = 'SimFi mail API <do_not_reply@'.$_SERVER['SERVER_NAME'].'>';
    }

    $this->logger = $logger;

  }//end public function __construct */

/*//////////////////////////////////////////////////////////////////////////////
// Getter and Setter
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @param boolean $create
   * @return TemplateWorkarea_Mail
   */
  public function getView($create = true)
  {

    if ($create && !$this->view)
      $this->view = new TemplateWorkarea_Mail();

    return $this->view;

  }//end public function getView */

  /**
   * Setter for the view
   */
  public function setView(TemplateWorkarea_Mail $view)
  {
    $this->view = $view;
  }//end public function setView */

  /**
   * the address to send the mail
   *
   * @param string $address
   */
  public function setAddress($address)
  {
    $this->address = $this->encode($address);
  }//end public function setAddress */

  /**
   * the address to send the mail
   *
   * @param string $address
   */
  public function addAddress($address)
  {

    if (is_array($address)) {
      if (!$this->address) {
        if ($addr = array_pop($address)) {
          $this->address = $this->encode($addr);
        }
      }

      foreach ($address as $addr) {
        $this->address .= ', '. $this->encode($addr);
      }
    } else {
      if (is_null($this->address)) {
        $this->address = $this->encode($address);
      } else {
        $this->address .= ', '. $this->encode($address);
      }
    }

  }//end public function setAddress */

  /**
   * @param string $subject
   */
  public function setSubject($subject)
  {
    $this->subject = $this->encode($subject);
  }//end public function setSubject */

  /**
   * @param string $priority
   */
  public function setPriority($priority)
  {

    $possible = array(
      '1' => '1 (Highest)',
      '2' => '2 (High)',
      '3' => '3 (Normal)',
      '4' => '4 (Low)',
      '5' => '5 (Lowest)'
    );

    if (isset($possible[$priority])) {
      $this->xPriority = $possible[$priority];
    }

  }//end public function setPriority */

  /**
   * set the mail importance
   *
   * <ul>
   * <li>high</li>
   * <li>normal</li>
   * <li>low</li>
   * </ul>
   *
   * @param string $importance
   */
  public function setImportance($importance)
  {

    $possible = array(
      'high',
      'normal',
      'low'
    );

    if (in_array($importance,$possible)) {
      $this->importance = $possible[$importance];
    }

  }//end public function setPriority */

  /**
   * set the reply address for the mail
   *
   * @param string $replyTo
   */
  public function setReplyTo($replyTo)
  {
    $this->replyTo = $this->encode( $replyTo);
  }//end public function setSubject */

  /**
   * Absender setzen
   *
   * @param string $sender
   */
  public function setSender($sender , $name = null)
  {

    if ($name) {
      $this->sender = $this->encode($name.' <'.$sender.'>');
    } else {
      $this->sender = $this->encode($sender);
    }

  }//end public function setSubject */

  /**
   * Common Copy Empfänger hinzufügen
   *
   * @param string $bbc
   * @param string $name
   */
  public function addBbc($bbc, $name = null)
  {

    if ($name) {
      $this->bbc[] = $this->encode($name.' <'.$bbc.'>');
    } else {
      $this->bbc[] = $this->encode($bbc);
    }

  }//end public function addBbc */

  /**
   * Common Copy Empfänger hinzufügen
   * @param string $cc
   * @param string $name
   */
  public function addCc($cc  , $name = null)
  {

    if ($name) {
      $this->cc[] = $this->encode($name.' <'.$cc.'>');
    } else {
      $this->cc[] = $this->encode($cc);
    }

  }//end public function addCc */

  /**
   * Plaintext Content der Mail setzen
   * @param string $plainText
   */
  public function setPlainText($plainText)
  {
    $this->plainText = $this->encode($plainText);
  }//end public function setPlainText */

  /**
   * Html Content der Mail setzen
   * @param string $htmlText
   */
  public function setHtmlText($htmlText)
  {
    $this->htmlText = $this->encode($htmlText);
  }//end public function setHtmlText */

  /**
   * @param string $mimeType
   */
  public function setMimeType($mimeType)
  {
    $this->mimeType =  $mimeType ;
  }//end public function setMimeType */

  /**
   * @param string $contentType
   */
  public function setContentType($contentType)
  {
    $this->contentType = $contentType;
  }//end public function setContentType */

  /**
   * @param string $charset
   */
  public function setCharset($charset)
  {
    $this->charset = $charset;
  }//end public function setCharset */

  /**
   * @param string $charset
   */
  public function addAttachment($fileName , $fullPath)
  {
    $this->attachment[$fileName] = $fullPath;
  }//end public function addAttachment */

  /**
   * @param string $fileName
   * @param string $fullPath
   */
  public function addEmbedded($fileName , $fullPath)
  {
    $this->embedded[$fileName] = $fullPath;
  }//end public function addEmbedded */

/*//////////////////////////////////////////////////////////////////////////////
// Logic
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @param string $fileName
   * @param string $attach
   * @param string $boundary
   */
  protected function buildAttachement($fileName, $attach, $boundary  )
  {

    if (!is_readable($attach)) {
      throw new Message_Exception('Failed to read the attachment '.$attach);
      return '';
    }

    $mimeType = IoMime::getMimeType($fileName);

    $attachment = '--'.$boundary.self::NLB;
    $attachment .= 'Content-Type: '.$mimeType.'; name="'.$fileName.'"'.self::NLB;
    $attachment .= 'Content-Transfer-Encoding: base64'.self::NLB;
    //$header .= 'Content-Disposition: attachment; filename="'.$fileName.'"'.self::NL.self::NL;
    $attachment .= 'Content-Disposition: attachment; filename="'.$fileName.'"'.self::NLB.self::NLB;
    $attachment .= chunk_split(base64_encode(file_get_contents($attach)));

    return $attachment;

  }//end protected function buildAttachement */

  /**
   * @param string $fileName
   * @param string $attach
   * @param string $boundary
   * @return string
   */
  protected function buildEmbeddedResource($fileName , $attach , $boundary  )
  {

    if (!is_readable($attach)) {
      throw new Message_Exception('Failed to read the attachment '.$attach);
      return '';
    }

    $mimeType = IoMime::getMimeType($fileName);

    $attachment = '--'.$boundary.self::NLB;
    $attachment .= 'Content-Type: '.$mimeType.'; name="'.$fileName.'"'.self::NLB;
    $attachment .= 'Content-Transfer-Encoding: base64'.self::NLB;
    //$header .= 'Content-Disposition: attachment; filename="'.$fileName.'"'.self::NL.self::NL;
    //$attachment .= 'Content-ID: <embeded@'.$fileName.'>'.self::NLB.self::NLB;
    $attachment .= 'Content-ID: <embeded@'.$fileName.'>'.self::NLB.self::NLB;
    $attachment .= chunk_split(base64_encode(file_get_contents($attach)));

    //embedd with: <img src="cid:embeded@news" width="120" >
    return $attachment;

  }//end protected function buildAttachement */

  /**
   * Senden der Nachricht
   * @param MessageContainter $msg
   * @return boolean
   */
  public function sendMessage( MessageContainter $msg )
  {

    $this->address = $msg->receiver;
    $this->htmlText = $msg->htmlText;
    $this->subject = $msg->subject;
    $this->attachment = $msg->attachment;

    if( $msg->sender) {
      $this->sender = $msg->sender;
    } else {
      $this->sender = Conf::getActive()->def_mail_sender;
    }

    if($msg->replyTo)
      $this->replyTo = $msg->replyTo;

    return $this->send();

  }//end public function sendMessage */

  /**
   * Senden der Nachricht
   * @param string $address
   * @return boolean
   */
  public function send($address = null)
  {
    // Variables
    if (!$address) {
      $address = $this->address;
    }

    // ohne adresse geht halt nix
    if (!$address) {
      throw new Message_Exception('Missing E-Mail Address');
    }

    $boundary = 'boundary-'.strtoupper(md5(uniqid(time())));

    $message = !is_null($this->htmlText)?$this->htmlText:$this->plainText;

    if ($this->htmlText) {
      $contentType = 'text/html';
    } else {
      $contentType = 'text/plain';
    }

    // Header
    $header = 'From: '.htmlspecialchars_decode($this->sender).self::NL;

    if ($this->replyTo) {
      $header .= 'Reply-To:'.htmlspecialchars_decode($this->replyTo).self::NL;
    }

    $header .= 'User-Agent: WebFrap'.self::NL;

    if ($this->returnPath) {
      $header .= 'Return-Path: <'.$this->returnPath.'>'.self::NL;
    }

    if ($this->importance) {
      $header .= 'Importance: '.$this->importance.self::NL;
    }

    if ($this->xPriority) {
      $header .= 'X-Priority: '.$this->xPriority.self::NL;
    }

    $header .= 'MIME-Version: '.$this->mimeType.self::NL;
    $header .= 'Content-Type: Multipart/Mixed; boundary="'.$boundary.'"'.self::NL;

    $body = 'This is a multi-part message in MIME format'.self::NL;
    $body .= '--'.$boundary.self::NL;
    $body .= 'Content-Type: '.$contentType.'; charset="'.$this->charset.'"'.self::NL;
    $body .= 'Content-Transfer-Encoding:  7bit'.self::NL;
    $body .= 'Content-Disposition: inline'.self::NL.self::NL;
    $body .= $message.self::NL.self::NL;

    if($this->attachment){
      foreach ($this->attachment as $fileName => $attach) {
        $body .= $this->buildAttachement($fileName, $attach, $boundary).self::NL;
      }
    }

    if($this->embedded){
      foreach ($this->embedded as $fileName => $attach) {
        $body .= $this->buildEmbeddedResource($fileName, $attach, $boundary).self::NL;
      }
    }

    $body .= '--'.$boundary.'--';

    /*
    Message::addMessage
    (
      "Send Message to Address: {$address} Subject: ".utf8_encode($this->subject)
    );
    */

    if(
      !mail(
        $address,
        $this->subject,
        $body,
        $header,
        "-f ".$this->sender
      )
    ) {
      throw new Message_Exception(
        'Failed to send Mail to'.$address
      );

      return false;
    } else {

      return true;
    }

  }//end protected function send */

  /**
   * Strings richtig encodieren
   *
   * @param string $data
   * @return string
   */
  protected function encode($data)
  {
    return utf8_decode($data);
  }//end protected function encode */

  /**
   * inhalt der nachricht leeren
   */
  public function cleanData()
  {

    $this->subject     = null;
    $this->plainText   = null;
    $this->htmlText    = null;

  }//end public function cleanData */

} // end class MessageMail

