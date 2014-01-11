<?php


include PATH_ROOT.Conf::getActive()->fw_root.'/vendor/securimage/securimage.php';

/**
 * 
 * @subpackage web_expert.cms
 */
class Contact_Controller extends MvcController
{


  protected $options           = array(
    'default' => array(
      'method'    => array( 'POST')
    ),
    'contact' => array(
      'method'    => array( 'POST')
    ),
  );

  /**
   * @service
   */
  public function do_default()
  {

    $this->do_contact();

  }//end public function do_default */

  /**
   * @service
   */
  public function do_contact()
  {

    $request = $this->getRequest();
    $console = $this->getConsole();
    $workarea = $console->tpl->getWorkArea('Cms');

    $securimage = new Securimage();

    $lang = $request->param('l', Validator::CNAME);

    if (!$lang)
      $lang = Conf::getActive()->lang;

    $rqt = new Contact_Request();
    $rqt->handleSaveRequest($request, array('contact' => new Contact_Entity()));

    $missing = new TArray();

    $conf = Conf::getActive();
    if(false !== $conf->captcha){
      $captcha = $securimage->check($_POST['captcha']);
    }


    $errors = array(
      'de' => array(
        'salutation' => 'Anrede ist ein Pflichtfeld.',
        'lastname' => 'Nachname ist ein Pflichtfeld.',
        'surname' => 'Vorname ist ein Pflichtfeld.',
        //'company' => 'Firma ist ein Pflichtfeld.',
        'email'   => 'E-Mail ist ein Pflichtfeld.',
        'comment' => 'Anfrage ist ein Pflichtfeld.',
        'captcha' => 'Der Sicherheitscode war inkorrekt: ',
        'error' => 'Die von Ihnen gemachten Angaben sind unvollständig. Bitte füllen Sie alle Pflichfelder aus.'
      ),
      'en' => array(
        'salutation' => 'Salution is required.',
        'lastname' => 'Lastname is require.',
        'surname' => 'Surname is required.',
        //'company' => 'Company is required.',
        'email'   => 'E-Mail is required.',
        'comment' => 'Inquiry is required.',
        'captcha' => 'The security code was invalid: ',
        'error' => 'The information you provided is incomplete. Please fill in all required fields.'
      ),
    );



    $rqt->errors = array();

    $error = false;

    if (false !== $conf->captcha) {
      if (!$captcha) {
        //$rqt->addError($errors[$lang]['captcha']);
        $error = true;
        $missing->captcha = true;
      }
    }


    if (!$rqt->entities['contact']->salutation) {
      //$rqt->addError($errors[$lang]['lastname']);
      $error = true;
      $missing->salutation = true;
    }

    if ($rqt->entities['contact']->isEmpty('lastname')) {
      //$rqt->addError($errors[$lang]['lastname']);
      $error = true;
      $missing->lastname = true;
    }

    if ($rqt->entities['contact']->isEmpty('surname')) {
      //$rqt->addError($errors[$lang]['surname']);
      $missing->surname = true;
      $error = true;
    }

    if ($rqt->entities['contact']->isEmpty('company')) {
      //$rqt->addError($errors[$lang]['company']);
      //$missing->company = true;
      //$error = true;
    }

    if ($rqt->entities['contact']->isEmpty('email')) {
      //$rqt->addError($errors[$lang]['email']);
      $missing->email = true;
      $error = true;
    }

    if ($rqt->entities['contact']->isEmpty('comment')) {
      //$rqt->addError($errors[$lang]['comment']);
      $missing->comment = true;
      $error = true;
    }

    if ($error)
      $rqt->addError($errors[$lang]['error']);

    if (!$rqt->errors) {

      try{
        $mailer = new MessageMail();

        $newContactMsg = new Contact_New_Message();
        $newContactMsg->render($rqt->entities['contact']);

        $confObj = Conf::getActive();

        if($confObj->def_reply_to)
          $newContactMsg->replyTo = $confObj->def_reply_to;
        $newContactMsg->receiver = $confObj->admin_mail;

        // wird vorab benötigt falls in send message eine exception kommt
        $copyContactMsg = new Contact_Copy_Message();
        $copyContactMsg->render($rqt->entities['contact'], $lang);
        $copyContactMsg->receiver = $rqt->entities['contact']->email;

        if ('force-an-error@debug.corp' == $rqt->entities['contact']->email) {
          throw new Exception('Debuggung');
        }

        $mailer->sendMessage($newContactMsg);

        if (Conf::getActive()->dev_mail) {
          $newContactMsg = new Contact_New_Message();
          $newContactMsg->render($rqt->entities['contact']);
          $newContactMsg->receiver = Conf::getActive()->dev_mail;
          $mailer->sendMessage($newContactMsg);
        }

        if (isset($_POST['contact']['send_copy'])) {
          $mailer->sendMessage($copyContactMsg);
        }

        $workarea->lang = $lang;
        //$workarea->addTemplate( 'contact_sent' );
        $workarea->rqtPage = 'contact_sent';
      }
      catch (Exception $exc){
        $workarea->addVar('errors',$rqt->errors);
        $workarea->lang = $lang;
        $workarea->addVar('contact',$rqt->entities['contact']);
        $workarea->addVar('missing',$missing);
        $workarea->addVar('message',$copyContactMsg);
        //$workarea->addTemplate( 'contact_failed' );
        $workarea->rqtPage = 'contact_failed';
      }

    } else {

      $workarea->addVar('errors',$rqt->errors);
      $workarea->lang = $lang;
      $workarea->addVar('contact',$rqt->entities['contact']);
      $workarea->addVar('missing',$missing);
      //$workarea->addTemplate( 'contact' );
      $workarea->rqtPage = 'contact';
    }



  }//end public function do_register */


}//end class UserRegister_Controller */
