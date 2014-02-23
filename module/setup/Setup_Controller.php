<?php

/**
 * @package com.BuizCore
 * @subpackage SimFi
 */
class Setup_Controller extends MvcController
{

    /**
     * 
     */
    public function do_default()
    {
        
        $request = $this->getRequest();
        $console = $this->getConsole();
        
        $setupBuilder = new SetupBuilder();
        $setupBuilder->syncProject();
        
        
    } // end public function do_default */
    
}//end class Setup_Controller */
