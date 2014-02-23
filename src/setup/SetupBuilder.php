<?php

/**
 * @package com.BuizCore
 * @subpackage SimFi
 */
class SetupBuilder
{
    
    
    /**
     * @var CmsSitemapReader
     */
    public $sitemap = null;
    
    /**
     * @var Conf
     */
    public $conf = null;
    
    /**
     * 
     */
    public function __construct()
    {
        $this->sitemap = new CmsSitemapReader();
        
        $this->conf = Conf::getActive();
        
        
    }
    

    public function syncProject()
    {
        
        $links = array();
        $routes = array();
        
        foreach($this->sitemap->langs as $lang){
            $links[$lang] = array();
        } 
        
        foreach ($this->sitemap->pages as $key => $page) {
            
            if(!isset($page['type']) || $page['type'] == 'text' ){
                $this->createPage_Text($key, $page);
            }
            
            if(isset($page['routes'])){
                foreach($page['routes'] as $routeLang => $route){
                    $routes[$route] = $key;
                    $links[$routeLang][$key] =  $route;
                }
            }
            
        }
        
        
        $codeRoutes = '';
        
        foreach ($routes as $routeKey => $routeTarget) {
            $codeRoutes .= "  '{$routeKey}' => '{$routeTarget}',".NL;
        }
        
        $codeLinks = '';
        
        foreach ($links as $lang => $linkData) {
            foreach ($linkData as $linkKey => $linkValue) {
                $codeLinks .= "\$this->links['{$lang}']['{$linkKey}'] = '{$linkValue}';".NL;
            }
        }
        
        
        $routePhp = <<<CODE
<?php

\$this->pageRoutes = array(
{$codeRoutes}
);
    
\$this->links = array();
{$codeLinks}

CODE;
        
        
        Fs::write($routePhp, CONF_PATH.'conf/routes.php');
        
    }//end public function syncProject */
    
    /**
     * @param string $key
     * @param string $page
     */
    public function createPage_Text($key, $page)
    {
        
        $pageFolder = PATH_ROOT.$this->conf->page_root.'/content/pages/'.$key.'/';
        

        
        if (!file_exists($pageFolder)) {
        
            Fs::mkdir($pageFolder);
    
    
            $pagePhp = <<<CODE
<?php

\$this->activePage = '{$key}';

CODE;
            if (isset($page['edit'])) {
                    
                    $pagePhp .= <<<CODE
\$this->editAble = true;

CODE;
            }
            Fs::write($pagePhp, $pageFolder.'page.php');
                        
                // template
                $pageTemplate = <<<CODE
<!-- start #page-content -->
<div id="page-content" >

    <section class="block text-n" >
        <h1></h1>
        
        <p></p>
        
        <ul class="list" >
            <li></li>
        </ul>
    </section>

</div>
<!-- end #page-content -->           
CODE;

                            
            Fs::write($pageTemplate, $pageFolder.'template.tpl');
                        
                        
            // template
            $textPhp = <<<CODE
<?php

CODE;
        
            foreach ($page['cont'] as $lang => $pageContent ) {

                if (isset($pageContent['title'])) {

                $textPhp .= <<<CODE
\$this->title['{$lang}'] = <<<TEXT
{$pageContent['title']}
TEXT;

CODE;
                }
                
                if (isset($pageContent['description'])) {
            
                    $textPhp .= <<<CODE
\$this->metaDescription['{$lang}'] = <<<TEXT
{$pageContent['description']}
TEXT;

CODE;
                }
            
            }

                    
            Fs::write($textPhp, $pageFolder.'text.php');

        
        }
        
    }//end public function createPage_Text */


}//end class SetupBuilder */
