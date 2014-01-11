<?php


/**
 * @package com.BuizCore
 * @subpackage SimFi
 */
class Contact_New_Message extends MessageContainter
{

  public function render( $data )
  {

    $conf = Conf::getActive();

    $this->subject = utf8_decode('Neue '.$conf->project_label.' Kontaktanfrage');

    $anrede = $data->salutation=='m'?'Herr':'Frau';

    $this->htmlText = <<<MAIL

<table cellpadding="3" cellspacing="2" >
{$this->renderHeader('Persönliche Daten')}
{$this->renderEntry('Anrede',$anrede)}
{$this->renderEntry('Nachname',$data->htmlSafe('lastname'))}
{$this->renderEntry('Vorname',$data->htmlSafe('surname'))}
{$this->renderEntry('Firmennamen',$data->htmlSafe('company'))}
{$this->renderEntry('Strasse',$data->htmlSafe('street').' '.$data->htmlSafe('street_num'))}
{$this->renderEntry('Postleitzahl',$data->htmlSafe('postalcode'))}
{$this->renderEntry('Ort',$data->htmlSafe('city'))}
{$this->renderEntry('Land',$data->htmlSafe('country'))}

{$this->renderHeader('Kontakt Daten')}
{$this->renderEmail('E-Mail Adresse',$data->htmlSafe('email'))}
{$this->renderEntry('Telefon',$data->htmlSafe('telefon'))}

{$this->renderComment('Anfrage:', $data->htmlSafe('comment'))}
</table>

MAIL;

    $this->htmlText = utf8_decode($this->htmlText);

  }


}//end class Contact_New_Message */
