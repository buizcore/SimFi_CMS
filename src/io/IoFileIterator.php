<?php



/**
 * @package com.BuizCore
 * @subpackage SimFi
 */
class IoFileIterator
  implements Iterator
{
////////////////////////////////////////////////////////////////////////////////
// Constantes
////////////////////////////////////////////////////////////////////////////////

  const RELATIVE = 1;
  
  const ABSOLUTE = 2;
  
  const FILE_ONLY = 3;

////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /** Folder in dem die Datei gespeichert war
   * @var string
   */
  protected $folder = null;
  
  /** Folder in dem die Datei gespeichert war
   * @var string
   */
  protected $relativeFolder = null;
  

  /** Folder in dem die Datei gespeichert war
   * @var string
   */
  protected $fRes = null;
  
  /**
   * Die Datei auf welcher sich aktuell der Dateizeiger befindet
   * @var string
   */
  protected $current = null;
  
  /**
   * Key für die Datei
   * @var string
   */
  protected $key = null;

  /**
   * @var IoFileIterator
   */
  protected $subFolder = null;
  
  /**
   * Sollen der Absolute Pfad, der Relative oder nur der FileMode
   * zurück gegeben werden
   * @var int
   */
  public $fileMode = IoFileIterator::RELATIVE;
  
  /**
   * Flag ob Subfolder ausgelesen werde sollen oder nicht
   * @var boolean
   */
  public $recursive = true;
  
  /**
   * Filter auf Dateiendungen
   * @var string
   */
  public $filter = null;
  
////////////////////////////////////////////////////////////////////////////////
// Magic
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $folder
   * @param int $mode
   * @param boolean $recursive
   * @param string $filter
   */
  public function __construct
  ( 
    $folder, 
    $mode       = IoFileIterator::RELATIVE, 
    $recursive  = true,
    $filter     = null
  )
  {
    
    $this->folder     = str_replace('//', '/', $folder );

    if( is_string( $mode ) )
    {
      $this->fileMode       = IoFileIterator::RELATIVE;
      $this->relativeFolder = $mode;
    }
    else 
    {
      $this->fileMode       = $mode;
      $this->relativeFolder = '/';
    }
    
    $this->recursive  = $recursive;
    
    if( $filter )
    {
      if( is_string( $filter ) )
        $this->filter     = explode( ',', $filter );
      else 
        $this->filter     = $filter;
    }
   

    if( is_dir( $folder ) )
    {
      $this->fRes = opendir( $folder );
      $this->next();
    }
    else 
    {
      UiConsole::debugLine( 'Tried to open nonexisting Folder: '.$folder );
    }

  }// public function __construct 
  
  /**
   */
  public function __desctruct( )
  {

    $this->close();

  }//end public function __desctruct */
  
  /**
   */
  public function close( )
  {
    
    if( is_resource( $this->fRes ) )
      closedir( $this->fRes );

  }//end public function __desctruct */

  /**
   * @return string
   */
  public function __toString()
  {
    return $this->folder;
  }//end public function __toString */

////////////////////////////////////////////////////////////////////////////////
// Getter and Setter
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return string
   */
  public function getName( )
  {
    return $this->folder;
  } // end public function getName */

////////////////////////////////////////////////////////////////////////////////
// Interface: Iterator
////////////////////////////////////////////////////////////////////////////////

  /**
   * @see Iterator::current
   */
  public function current ()
  {
    
    return $this->current;
    
  }//end public function current */

  /**
   * @see Iterator::key
   */
  public function key ()
  {
    
    return $this->key;
    
  }//end public function key */
  
  /**
   * @see Iterator::next
   */
  public function next ()
  {
    
    if( !is_resource($this->fRes) )
      return null;
    
    $repeat   = true;
    $current  = null;
    
    while( $repeat ) 
    {
      
      if( $this->subFolder )
      {
        $nextSub = $this->subFolder->next();
        $nextKey = $this->subFolder->key();
        
        if( $nextSub )
        {
          $this->current = $nextSub;
          $this->key     = $nextKey;
          return $this->current;
        }
        else 
        {
          $this->subFolder = null;
          $this->current   = null;
          continue;
        }
      }

      $current    = readdir( $this->fRes );
      $currentKey = null;
        
      // dirty.... so what?
      if( '.' == $current  )
        continue;
        
      if( '..' == $current )
        continue;

      if( $current )
      {
        if( is_dir( $this->folder.'/'.$current )  )
        {
          
          if( !$this->recursive )
            continue;
          
          // wenn current ein ordner ist wird ers über ihn iteriert bevor 
          // das nächste element des aktuellen ordners ausgelesen wird
          $this->subFolder = new IoFileIterator
          ( 
            $this->folder.'/'.$current.'/',
            $this->relativeFolder.'/'.$current.'/',
            true,
            $this->filter
          );
          
          $current    = $this->subFolder->current();
          $currentKey = $this->subFolder->key();
          
          if( !$current )
          {
            $this->subFolder = null;
            $this->current   = null;
            continue;
          }
          
          // auf eine dateiendung prüfen
          if( $this->filter )
          {
            
            $info = pathinfo(str_replace( '//', '/', $this->folder.'/'.$current ));
            
            if( isset($info['extension']) && !in_array( strtolower('.'.$info['extension']), $this->filter  )  )
              continue;
          }
          
        }
        else 
        {

          // auf eine dateiendung prüfen
          if( $this->filter )
          {
            
            $info = pathinfo(str_replace( '//', '/', $this->folder.'/'.$current ));
            
            if( isset($info['extension']) && !in_array( strtolower('.'.$info['extension']), $this->filter  )  )
              continue;
            
          }
          
          // den rückgabe modus auswerten
          if( $this->fileMode != IoFileIterator::FILE_ONLY )
          {
            $current    = str_replace( '//', '/', $this->folder.'/'.$current );
            $currentKey = str_replace( '//', '/', $this->relativeFolder.'/'.$current );
          } 
         
        }
        
      }
      else 
      {
        $this->current = null;
        return null;
      }

      
      $repeat = false;
    } 
    
    // sicher stellen, dass die pfade korrekt sind
    if( $current )
      $this->current = str_replace( array('../','//'), array('/','/'), $current ) ;
    else 
      $this->current = null;
      
    $this->key = $currentKey;
    
    return $this->current;
    
  }//end public function next */

  /**
   * @see Iterator::rewind
   */
  public function rewind ()
  {
    
    if( is_resource($this->fRes))
      rewinddir($this->fRes);
      
    $this->subFolder = null;
    
    $this->next();
    
  }//end public function rewind */

  /**
   * @see Iterator::valid
   */
  public function valid ()
  {
    return $this->current ? true:false;
  }//end public function valid */

} // end class IoFileIterator

