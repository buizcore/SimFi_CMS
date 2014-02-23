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
    public $sitemMap = null;
    
    /**
     * @var Conf
     */
    public $conf = null;
    
    /**
     * 
     */
    public function __construct()
    {
        $this->sitemMap = new CmsSitemapReader();
        
        $this->conf = Conf::getActive();
        
        
    }
    

    public function syncProject()
    {
        
        foreach ($this->sitemMap->pages as $key => $page) {
            
            if(!isset($page['type']) || $page['type'] == 'text' ){
                $this->createPage_Text($key, $page);
            }
            
        }
        
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
