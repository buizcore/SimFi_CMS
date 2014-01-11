<?php



/**
 * 
 * @subpackage web_expert
 */
class MvcController extends EnvironmentCore
{


  /**
   * Liste der Services welche über diesen Controller angeboten werden.
   *
   * Listet für jeden Service die HTTP Methoden die Valide sind, sowie
   * die Attribute und Datenfelder welcher akzeptiert werden
   *
   * Kann zu XML oder Json Serialisiert werden
   *
   * Klappt nicht?
   * Häufige Fehler / Fehlerquellen:
   *  - eintrag in callAble vergessen
   *  - eintrag nicht lowercase
   *  - Buchstabendreher
   *  - methode ist nicht public und kann deshalb nicht aufgerufen werden
   *  - call tripple enthält weniger als genau 3 werte
   *  - beim aufruf das c= vor dem tripple vergessen
   *  - ? anstelle von & als url trenner
   *
   * @example
   * protected $options = array
   * (
   *   'helloworld' => array
   *   (
   *     'method'    => array( 'GET', 'POST' ),
   *     'interface' => array
   *     (
   *        'GET' => array
   *       (
   *         'param' => array
   *          (
   *           'name' => array( 'type' => 'string', 'semantic' => 'The Name of the Whatever', 'required' => true, 'default' => 'true' ),
   *          ),
   *       )
   *       'POST' => array
   *       (
   *          'param' => array
   *          (
   *
   *          ),
   *          'data' => array
   *          (
   *
   *         )
   *       )
   *     ),
   *     'views'       => array
   *     (
   *       'maintab',
   *       'window'
   *     ),
   *     'access'       => 'auth_required',
   *     'description' => 'Hello World Method'
   *     'docref'       => 'some_link',
   *     'author'       => 'author <author@mail.addr>'
   *   )
   * );
   *
   * @var array
   */
  protected $options           = array();


  /**
   * die vom request angeforderte methode auf rufen
   * @param string $action
   */
  public function execute(  $action )
  {

    $actionName = 'do_'.$action;

    $lAct = strtolower($action);

    if( isset($this->options[$lAct]) ){
      if(isset($this->options[$lAct]['session'])){
        $this->startSession();
      }
    }

    if( method_exists( $this, $actionName ) ) {
      $this->$actionName( );
    } else {
      if( method_exists( $this, 'do_help' ) ) {
        $this->do_help( $action );
      } else {
        $this->invalidRequest( $action );
      }
    }

  }//end public function execute */

  /**
   * @param string $action
   */
  protected function invalidRequest( $action )
  {

    $this->console->error( "Invalid Request ".$action );

  }//end protected function invalidRequest */

  /**
   */
  public function do_help( $action )
  {
    $this->console->out( 'HELP '.$action );
  }//end public function do_help */

  /**
   */
  public function startSession()
  {

    session_start();

  }//end public function startSession */

}//end class MvcController */
