<?php

/**
 * Erzeugt eine neue Instanz eines HTMLPurifier mit den festgelegten Eigenschaften. Die Eigenschaften
 * werden durch die Vererbung an alle abgeleiteten Klassen weitergegeben.
 * Erlaubt sind die meisten Tags für Textauszeichnung, sonst nichts.
 *
 * Erlaubte Elemente: ...
 * Verbotene Elemente: ...
 *
 * Erlaube Attribute: ...
 * Verbotene Attribute: ...
 *
 * Erlaubte Klassen: ...
 * Verbotene Klassen: ...
 *
 * Sonstige Einstellungen: ...
 *
 */
class UtilHtmlCleaner_Config extends HTMLPurifier_Config
{

  /**
   * Enthält alle erlaubten HTML Elemente
   * @var array
   */
  public $htmlAllowedElements = array();

  /**
   * Enthält alle verbotenen HTML Elemente
   * @var array
   */
  public $htmlForbiddenElements = array();

  /**
   * Enthält alle erlaubten Klassen
   * @var array
   */
  public $attrAllowedClasses = array();

  /**
   * Enthält alle verbotenen Klassen
   * @var array
   */
  public $attrForbiddenClasses = array();

  /**
   * Enthält alle erlaubten HTML Attribute
   * @var array
   */
  public $htmlAllowedAttributes = array();

  /**
   * Enthält alle verbotenen Attribute
   * @var array
   */
  public $htmlForbiddenAttributes = array();

  /**
   * Konfigurationsobjekt
   * @var HTMLPurifier_Config
   */
  private $config = null;

  public function __construct ()
  {

    $this->init();

    $this->config = HTMLPurifier_Config::createDefault();
    $this->config->set("Cache.DefinitionImpl", null); // TODO: remove this later!


    $this->config->loadArray(
      array(
          "HTML.AllowedElements" => implode(", ", $this->htmlAllowedElements),
          "HTML.AllowedAttributes" => implode(", ", $this->htmlAllowedAttributes),
          "Attr.AllowedClasses" => implode(", ", $this->attrAllowedClasses),
          "CSS.AllowedFonts" => '',
          "AutoFormat.RemoveEmpty" => true,
          "AutoFormat.RemoveEmpty.RemoveNbsp" => true
      ));
  }

  /**
   * Initialisiert die Arrays der Basisklasse mit den entsprechenden Daten.
   */
  public function init ()
  {
    // Folgende Elemente sind nicht unterstützt von HTMLPurifier
    // alt, title, fieldset, legend
    $this->htmlAllowedElements[] = "strong";
    $this->htmlAllowedElements[] = "em";
    $this->htmlAllowedElements[] = "span";

    $this->htmlAllowedElements[] = "ol";
    $this->htmlAllowedElements[] = "ul";
    $this->htmlAllowedElements[] = "li";

    $this->htmlAllowedElements[] = "a";
    $this->htmlAllowedElements[] = "p";
    $this->htmlAllowedElements[] = "br";

    $this->htmlAllowedElements[] = "div";

    $this->htmlAllowedElements[] = "h3";

    $this->htmlAllowedAttributes[] = "a.href,a.target";

    $this->attrAllowedClasses[] = "";
  }

  public function getConfig ()
  {

    return $this->config;
  }
}