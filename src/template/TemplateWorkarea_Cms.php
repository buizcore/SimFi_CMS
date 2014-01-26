<?php

/**
 * @package com.BuizCore
 * @subpackage SimFi
 */
class TemplateWorkarea_Cms extends TemplateWorkarea
{

    /**
     *
     * @var string
     */
    public $contentType = 'text/html';

    /**
     *
     * @var string
     */
    public $index = 'default';

    /**
     *
     * @var string
     */
    public $tplPath = null;

    /**
     *
     * @var string
     */
    public $pagePath = null;

    /**
     *
     * @var string
     */
    public $cmsTemplate = null;

    /**
     *
     * @var array
     */
    public $texts = array(
        'de' => array(),
        'en' => array()
    );

    /**
     *
     * @var array
     */
    public $metaTags = array(
        'de' => array(),
        'en' => array()
    );

    /**
     *
     * @var array
     */
    public $metaDescription = array(
        'de' => '',
        'en' => ''
    );

    /**
     *
     * @var [function]
     */
    public $renderers = array();

    /**
     * Rückgaben bei ajax requests
     * 
     * @var []
     */
    public $jsonData = array();

    /**
     *
     * @var array
     */
    public $links = array();

    /**
     *
     * @var array
     */
    public $images = array();

    /**
     *
     * @var array
     */
    public $values = array();

    /**
     * Index der bereits geladenen text files
     * 
     * @var array
     */
    public $textIdx = array();

    /**
     * Index der bereits geladenen Value Files
     * 
     * @var array
     */
    public $valIdx = array();

    /**
     *
     * @var array
     */
    public $textSources = array();

    /**
     *
     * @var string
     */
    public $scope = 'cms';

    /**
     *
     * @var string
     */
    public $mode = null;

    /**
     * Wurde der admin mode aktiviert?
     * 
     * @var boolean
     */
    public $adminMode = false;

    /**
     * html string für das admin control panel soweit vorhanden
     * 
     * @var string
     */
    public $adminControls = null;

    /**
     * kann die seite im admin panel bearbeitet werden oder ist sie statisch?
     * 
     * @var boolean
     */
    public $editAble = false;

    /**
     * Liste mit lokalen nur für die aktuelle seite zu ladenten JS Files
     * 
     * @var array
     */
    public $localJsFiles = array();

    /**
     * Liste mit lokalen nur für die aktuelle seite zu ladenten Css Files
     * 
     * @var array
     */
    public $localCssFiles = array();

    /**
     *
     * @return string
     */
    public function renderMetaTags()
    {
        return isset($this->metaTags[$this->lang]) ? implode(',', $this->metaTags[$this->lang]) : '';
    } // end public function renderMetaTags */
    
    /**
     *
     * @return Ambigous <string, multitype:>
     */
    public function renderMetaDesc()
    {
        return isset($this->metaDescription[$this->lang]) ? $this->metaDescription[$this->lang] : '';
    } // end public function renderMetaDesc */
    
    /**
     *
     * @return string
     */
    public function render()
    {
        $v = $this->vars;
        
        if (! is_null($this->conf->page_def_editable)) {
            $this->editAble = $this->conf->page_def_editable;
        }
        
        // wenn ajax dann geben wir json zurück
        if (isset($_GET['ajax'])) {
            $this->contentType = 'text/json';
        }
        
        // user ist eingeloggt
        if (isset($_SESSION['user'])) {
            $this->adminMode = true;
        }
        
        if (isset($_SESSION['user']) || isset($_GET['do'])) {
            $this->scope = 'admin';
        }
        
        if (isset($_GET['do'])) {
            $this->mode = $_GET['do'];
        }
        
        if (isset($_POST) && 'login' == $this->mode) {
            
            $this->mode = 'admin';
            
            if (isset($_POST['user'])) {
                
                $password = $this->getVal('users', $_POST['user']);
                if ($password) {
                    if ($password === sha1($_POST['password'])) {
                        $_SESSION['user'] = $_POST['user'];
                        $this->adminMode = true;
                    }
                }
            }
        }
        
        if ('admin' == $this->mode && ! $this->adminMode) {
            
            $this->rqtPage = 'home';
        }
        
        if ('logout' == $this->mode) {
            unset($_SESSION['user']);
            $this->adminMode = false;
        }

        
        $this->loadPageData();
        
        // handle the requested action
        if ($this->mode) {
            if (! $this->handelAction($this->mode)) {
                if ('save' === $this->mode && isset($_POST) && $this->adminMode) {
                    $this->savePage($this->mode);
                    return;
                }
                
                if ('upload' === $this->mode && isset($_POST) && $this->adminMode) {
                    $this->savePage($this->mode);
                    return;
                }
            }
        }
        
        // wenn ajax dann ist hier schluss
        if (isset($_GET['ajax'])) {
            $this->renderedContent = json_encode($this->jsonData);
            return $this->renderedContent;
        }
        
        // laden des contents
        ob_start();
        
        if ($this->cmsTemplate) {
            
            if ($this->conf->platform) {
                $tplName = PATH_ROOT.$this->conf->page_root.'/content/templates/'.$this->cmsTemplate.'.'.$this->conf->platform.'.tpl';
            } else {
                $tplName = PATH_ROOT.$this->conf->page_root.'/content/templates/'.$this->cmsTemplate.'.tpl';
            }
            
            if (Fs::exists($tplName))
                include $tplName;
            else
                echo 'Missing Template '.$this->cmsTemplate.NL;
        } else {
            
            if ($this->conf->platform) {
                $tplName = PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/'.$this->conf->platform.'.template.tpl';
            } else {
                $tplName = PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/template.tpl';
            }
            
            if (Fs::exists($tplName))
                include $tplName;
            else
                echo 'Missing Template for '.$this->rqtPage.NL;
        }
        
        $maincontent = ob_get_contents();
        ob_end_clean();
        
        // laden des cms templates
        ob_start();
        
        if ($this->conf->platform) {
            $idxPath = PATH_ROOT.$this->conf->page_root.'/content/layouts/'.$this->conf->platform.'/'.$this->index.'.tpl';
        } else {
            $idxPath = PATH_ROOT.$this->conf->page_root.'/content/layouts/'.$this->index.'.tpl';
        }
        
        if (Fs::exists($idxPath))
            include $idxPath;
        else
            echo 'Missing Index '.$this->index.NL;
        
        $redered = ob_get_contents();
        ob_end_clean();
        
        $this->renderedContent = $redered;
        
        return $this->renderedContent;
    } // end public function render */
    
    /**
     * Laden der CMS / Metadaten zum rendern der Seite
     * Keine Ausgabe gestattet
     */
    protected function loadPageData()
    {
        
        $this->tplPath = PATH_ROOT.$this->conf->page_root.'/content/templates/';
        $this->pagePath = PATH_ROOT.$this->conf->page_root.'/content/pages/';
        
        if ($this->conf->platform) {
        
            if (file_exists(PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/'.$this->conf->platform.'.page.php')) {
        
                include PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/'.$this->conf->platform.'.page.php';
        
            } else if (file_exists(PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/page.php')) {
        
                include PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/page.php';
        
            } else {
        
                $this->rqtPage = 'error_404';
                include PATH_ROOT.$this->conf->page_root.'/content/pages/error_404/page.'.$this->conf->platform.'.php';
            }
            
            if($this->rqtPage != 'error_404'){
                
                if (file_exists(PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/'.$this->conf->platform.'.text.php')) {
                
                    include PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/'.$this->conf->platform.'.text.php';
                
                } else if (file_exists(PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/custom.text.php')) {
                
                    include PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/custom.text.php';
                
                } else if (file_exists(PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/text.php')) {
                
                    include PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/text.php';
                }
            }
            
        } else {
        
            if (file_exists(PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/custom.page.php')) {
        
                include PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/custom.page.php';
        
            } else if (file_exists(PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/page.php')) {
        
                include PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/page.php';
        
            } else {
        
                $this->rqtPage = 'error_404';
                include PATH_ROOT.$this->conf->page_root.'/content/pages/error_404/page.php';
            }
            
            if($this->rqtPage != 'error_404'){
            
                if (file_exists(PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/custom.text.php')) {
            
                    include PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/custom.text.php';
            
                } else if (file_exists(PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/text.php')) {
            
                    include PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/text.php';
                }
            }
        }
        
        if (file_exists(PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/logic.php')) {
            include PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/logic.php';
        }
        
        include PATH_ROOT.$this->conf->page_root.'/content/links/'.$this->lang.'.php';
        
    }//end protected function loadPageData */
    
    
    /**
     *
     * @param string $lang            
     * @return string
     */
    public function text($key, $lang = null)
    {
        if (! $lang)
            $lang = $this->lang;
        
        if (! isset($this->texts[$lang][$key])) {
            $tmp = explode('.', $key);
            
            if (isset($this->textIdx[$tmp[0]])) {
                return 'missing key '.$key;
            }
            
            $this->textIdx[$tmp[0]] = true;
            if (file_exists(PATH_ROOT.$this->conf->page_root.'/content/texts/'.$tmp[0].'.php')) {
                
                include PATH_ROOT.$this->conf->page_root.'/content/texts/'.$tmp[0].'.php';
            } else {
                
                return 'missing source '.$key;
            }
        }
        
        if (! isset($this->texts[$lang][$key])) {
            return 'missing key '.$key;
        }
        
        return $this->texts[$lang][$key];
    } // end public function text */
    
    /**
     *
     * @param string $key            
     * @param string $subKey            
     * @param string $lang            
     * @return string
     */
    public function money($key, $subKey, $append, $else, $prepend = '')
    {
        if (! isset($this->values[$key])) {
            if (! $this->loadValues($key)) {
                return 'missing value '.$key;
            }
            if (! isset($this->values[$key])) {
                return 'missing value '.$key;
            }
        }
        
        if ((is_null($this->values[$key][$subKey]))) {
            return $else;
        }
        
        $val = $this->values[$key][$subKey];
        
        if ('' == trim($val) || 0 == $val) {
            return $else;
        }
        
        if (isset($_SESSION['user'])) {
            return ' <span class="money" >'.$prepend.'</span><span class="money ecl" data-key="'.$key.'['.$subKey.']" >'.number_format($val, 2, ',', '.').'</span><span class="money" >'.$append.'</span>';
        }
        
        return ' <span class="money" >'.$prepend.' '.number_format($val, 2, I18n::$dec[$this->lang], I18n::$mil[$this->lang]).$append.'</span>';
    } // end public function money */
    
    /**
     *
     * @param string $key            
     * @param string $subKey            
     * @return string
     */
    public function getVal($key, $subKey = null)
    {
        if (! isset($this->values[$key])) {
            if (! $this->loadValues($key)) {
                return 'missing value '.$key;
            }
            if (! isset($this->values[$key])) {
                return 'missing value '.$key;
            }
        }
        
        if (is_null($subKey)) {
            return @! is_null($this->values[$key]) ? $this->values[$key] : null;
        }
        
        // ... well... whatever
        return @! is_null($this->values[$key][$subKey]) ? $this->values[$key][$subKey] : null;
        
    } // end public function getVal */
    
    /**
     * Eine CMS konforme / i18n routebare URL zusammen bauen
     * @param string $key            
     * @param string $subKey          
     * @param string $lang              
     * @param boolean $ssl                 
     * @param $actionLink $ssl         
     * @return string
     */
    public function cmsLink($key, $subKey = null, $lang = null, $ssl = false, $actionLink = false)
    {
        if (!$lang)
            $lang = $this->lang;
        
        if($this->conf->lang == $lang){
            $lang = '';
        } else {
            $lang .= '/';
        }
        
        $ending = $actionLink?'':'.html';
        
        $subLink = '';
        if($subKey)
            $subLink = '/'.$subKey ;
        
        if ($ssl) {
            
            return isset($this->links[$key]) 
                ? $this->conf->ssl_base_url.$lang.$this->links[$key].$subLink.$ending 
                : $this->conf->ssl_base_url.$lang.$key.$subLink.$ending;
        } else {
            
            return isset($this->links[$key]) 
                ? $this->conf->base_url.$lang.$this->links[$key].$subLink.$ending
                : $this->conf->base_url.$lang.$key.$subLink.$ending;
        }
        
    } // end public function cmsLink */
    
    /**
     * checken ob in text key existiert
     * @param string $lang            
     * @return string
     */
    public function has($key, $lang = null)
    {
        if (! $lang)
            $lang = $this->lang;
        
        if (! isset($this->texts[$lang][$key])) {
            $tmp = explode('.', $key);
            
            if (isset($this->textIdx[$tmp[0]])) {
                return false;
            }
            
            $this->textIdx[$tmp[0]] = true;
            if (file_exists(PATH_ROOT.$this->conf->page_root.'/content/texts/'.$tmp[0].'.php')) {
                include PATH_ROOT.$this->conf->page_root.'/content/texts/'.$tmp[0].'.php';
            }
        }
        
        return isset($this->texts[$lang][$key]);
    } // end public function has */
    
    /**
     *
     * @param string $key
     * @return string
     */
    protected function getPageElemPath($key)
    {
        if ($this->conf->platform) {
            
            if (file_exists(SIMFI_CONTENT_PATH.'content/pages/'.$this->rqtPage.'/'.$this->conf->platform.'.'.$key.'.php')) {
                
                return SIMFI_CONTENT_PATH.'/content/pages/'.$this->rqtPage.'/'.$this->conf->platform.'.'.$key.'.php';
                
            } else  if (file_exists(PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/custom.page.php')) {
                    
                include PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/custom.page.php';
                
                if (file_exists(PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/logic.php')) {
                    include PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/logic.php';
                }
            } else  if (file_exists(PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/page.php')) {
                    
                include PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/page.php';
                
                if (file_exists(PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/logic.php')) {
                    include PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/logic.php';
                }
            } else {
                
                $this->rqtPage = 'error_404';
                include PATH_ROOT.$this->conf->page_root.'/content/pages/error_404/page.'.$this->conf->platform.'.php';
            }
        } else {
            
            if (file_exists(PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/custom.page.php')) {
                
                include PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/custom.page.php';
                
                if (file_exists(PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/logic.php')) {
                    
                    include PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/logic.php';
                }
            } else if (file_exists(PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/page.php')) {
                    
                include PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/page.php';
                
                if (file_exists(PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/logic.php')) {
                    include PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/logic.php';
                }
            } else {
                
                $this->rqtPage = 'error_404';
                include PATH_ROOT.$this->conf->page_root.'/content/pages/error_404/page.php';
            }
  
        }
        
    } // end protected function getPageElemPath */
    
    /**
     *
     * @param string $key            
     * @return string
     */
    protected function loadValues($key)
    {
        $tmp = explode('.', $key);
        
        if (isset($this->valIdx[$tmp[0]])) {
            return true;
        }
        
        $this->valIdx[$tmp[0]] = true;
        
        if (file_exists(PATH_ROOT.$this->conf->page_root.'/content/data/'.$tmp[0].'.php')) {
            include PATH_ROOT.$this->conf->page_root.'/content/data/'.$tmp[0].'.php';
            return true;
        } else {
            return false;
        }
    } // end protected function loadValues */
    
    /**
     *
     * @param string $key            
     * @return string
     */
    protected function embededJS()
    {
        if ($this->conf->platform) {
            $scriptName = PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/'.$this->conf->platform.'.js.php';
        } else {
            $scriptName = PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/js.php';
        }
        
        if (Fs::exists($scriptName))
            include $scriptName;
    } // end protected function embededJS */
    
    /**
     *
     * @param string $key            
     * @return string
     */
    protected function embededCss()
    {
        if ($this->conf->platform) {
            $scriptName = PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/'.$this->conf->platform.'.css.php';
        } else {
            $scriptName = PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/css.php';
        }
        
        if (Fs::exists($scriptName))
            include $scriptName;
    } // end protected function embededJS */
    
    /**
     */
    protected function handelAction($action = null)
    {
        if ($action) {
            
            $actionName = PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/actions/'.$action.'.php';
            
            if (file_exists($actionName)) {
                include $actionName;
                return true;
            }
        }
        
        return false;
    } // end protected function savePage */
    
    /**
     */
    protected function savePage($action = null)
    {
        require_once PATH_ROOT.$this->conf->fw_root.'/vendor/htmlpurifier/library/HTMLPurifier.auto.php';
        
        $defConf = new UtilHtmlCleaner_Config();
        $config = $defConf->getConfig();
        $config->set('Core.Encoding', 'UTF-8');
        
        $purifier = new HTMLPurifier($config);
        
        if (isset($_POST['text'])) {
            foreach ($_POST['text'] as $textKey => $text) {
                if (is_array($text)) {
                    $this->texts[$this->lang][$textKey] = $text;
                } else {
                    $this->texts[$this->lang][$textKey] = $purifier->purify($text);
                }
            }
        }
        
        $pageWriter = new CmsDataWriter($this);
        $pageWriter->write(PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/custom.page.php');
        chmod(PATH_ROOT.$this->conf->page_root.'/content/pages/'.$this->rqtPage.'/custom.page.php', 0775);
        
        $this->jsonData['status'] = 'ok';
    } // end protected function savePage */
    
}//end class TemplateWorkarea_Cms */