<?php


/**
 * Ein Nachrichten Container
 * @package com.BuizCore
 * @subpackage SimFi
 *
 */
class MessageContainter
{

  public $subject = null;

  public $sender = null;

  public $receiver = null;

  public $replyTo = null;

  public $plainText = null;

  public $htmlText = null;

  public $attachment = null;


  public function renderEntry($label, $value)
  {

    if (''==trim($value)) {
      return '';
    }

    return <<<HTML
  <tr>
    <td style="width:200px;" >{$label}</td>
    <td>{$value}</td>
  </tr>
HTML;

  }

  public function renderEmail($label, $value)
  {

    if (''==trim($value)) {
      return '';
    }

    return <<<HTML
  <tr>
    <td style="width:200px;" >{$label}</td>
    <td><a href="mailto:{$value}" >{$value}</a></td>
  </tr>
HTML;

  }

  public function renderHeader($label)
  {

    return <<<HTML
  <tr>
    <td colspan="2" ><strong>{$label}</strong></td>
  </tr>
HTML;

  }

  public function renderComment($label, $comment)
  {


    return <<<HTML
  <tr>
    <td colspan="2" ><strong>{$label}</strong></td>
  </tr>
  <tr>
    <td colspan="2" >{$comment}</td>
  </tr>
HTML;

  }

  public function renderBlank()
  {

    return <<<HTML
  <tr>
    <td colspan="2" >&nbsp;&nbsp;</td>
  </tr>
HTML;

  }

} // end class MessageContainter

