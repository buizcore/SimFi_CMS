<?php


/**
 * 
 * @subpackage web_expert.cms
 */
class Contact_Copy_Message extends MessageContainter
{

  /**
   * @param $data
   * @param string $lang
   */
  public function render( $data, $lang='de' )
  {

    if ($lang=='de') {
      $this->renderDe($data);
    } else {
      $this->renderEn($data);
    }

  }//end public function render */





  public function renderDe( $data )
  {

    $conf = Conf::getActive();

    $this->subject = utf8_decode('Ihre Kopie der '.$conf->project_label.' Kontaktanfrage');

    $anrede = trim($data->salutation)=='m'?'Herr':'Frau';

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


  public function renderEn( $data )
  {

    $conf = Conf::getActive();

    $this->subject = utf8_decode('Private copy of your '.$conf->project_label.' contact request');

    $anrede = trim($data->salutation)=='m'?'Mr.':'Mrs.';

    $this->htmlText = <<<MAIL
<table cellpadding="3" cellspacing="2" >
{$this->renderHeader('Personal data')}
{$this->renderEntry('salutation',$anrede)}
{$this->renderEntry('lastname',$data->htmlSafe('lastname'))}
{$this->renderEntry('surname',$data->htmlSafe('surname'))}
{$this->renderEntry('company',$data->htmlSafe('company'))}
{$this->renderEntry('street / no.',$data->htmlSafe('street').' '.$data->htmlSafe('street_num'))}
{$this->renderEntry('ZIP',$data->htmlSafe('postalcode'))}
{$this->renderEntry('city',$data->htmlSafe('city'))}
{$this->renderEntry('country',$data->htmlSafe('country'))}

{$this->renderHeader('Contact data')}
{$this->renderEmail('email',$data->htmlSafe('email'))}
{$this->renderEntry('phone',$data->htmlSafe('telefon'))}

{$this->renderComment('your inquiry:', $data->htmlSafe('comment'))}
</table>

MAIL;
    $this->htmlText = utf8_decode($this->htmlText);

  }

}//end class Contact_Copy_Message */
