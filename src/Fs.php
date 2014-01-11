<?php

/**
 * @package com.BuizCore
 * @subpackage SimFi
 */
class Fs
{

    /**
     * Den aktuellen Pfad des Scriptes ändern
     * 
     * @param string $path            
     */
    static function pathIsAbsolute($path)
    {
        return ($path[0] == '/');
    } // end static function pathIsAbsolute */
    
    /**
     * Den aktuellen Pfad des Scriptes ändern
     * 
     * @param string $path            
     */
    static function chdir($path)
    {
        chdir($path);
    } // end static function chdir */
    
    /**
     * Der aktuellen working path auslesen
     * 
     * @return string
     */
    static function actualPath()
    {
        return getcwd();
    } // edn static function actualPath */
    
    /**
     * Den aktuellen Pfad des Scriptes ändern
     * 
     * @param string $path            
     */
    static function exists($path)
    {
        return file_exists($path);
    } // end static function exists */
    
    /**
     * Datei oder Ordner rekursiv kopieren
     *
     * @param string $src            
     * @param string $target            
     * @param boolean $isFolder            
     */
    static function copy($src, $target, $isFolder = true)
    {
        if ($isFolder) {
            if (! file_exists($target))
                Fs::mkdir($target);
        } else {
            Fs::touchFileFolder($target);
        }
        
        if ($isFolder)
            Process::run("cp -rf $src $target");
        else
            Process::run("cp $src $target");
        
        return true;
    } // end static function copy */
    
    /**
     * Datei oder Ordner rekursiv kopieren
     *
     * @param string $src            
     * @param string $target            
     * @param boolean $isFolder            
     */
    static function copyContent($src, $target, $isFolder = true)
    {
        if ($isFolder && ! file_exists($target)) {
            Fs::mkdir($target);
        }
        
        $src = realpath($src) . '/*';
        
        Process::run("cp -rf $src $target");
        
        return true;
    } // end static function copyContent */
    
    /**
     * Datei oder Verzeichniss rekursiv löschen
     * 
     * @param string $path            
     */
    static function del($path)
    {
        Process::run("rm -rf $path");
    } // end static function del */
    
    /**
     * Datei oder Verzeichniss rekursiv löschen
     * 
     * @param string $path            
     */
    static function delFileDir($filename)
    {
        if (! file_exists($filename)) {
            throw new WebExpertException("File {$filename} not exists.");
        }
        
        $dir = dirname($filename);
        
        Process::run("rm -rf {$dir}");
    } // end static function delFileDir */
    
    /**
     * Ein Verzeichnis, bei bedarf rekursiv, erstellen
     *
     * @param string $path            
     * @param int $mode            
     */
    static function mkdir($path, $mode = 0777)
    {
        if (! file_exists($path)) {
            mkdir($path, $mode, true);
        }
        
        return true;
    } // end static function mkdir */
    
    /**
     * Ein Verzeichnis, bei bedarf rekursiv, erstellen
     *
     * @param string $path            
     * @param int $mode            
     */
    static function isDir($path)
    {
        if (! file_exists($path))
            return false;
        
        return is_dir($path);
    } // end static function mkdir */
    
    /**
     * Pürfen des Dateitypes
     *
     * @param string $fileName            
     * @param string $ending            
     */
    static function isA($fileName, $ending)
    {
        $pathInfo = pathinfo($fileName);
        
        if (! isset($pathInfo['extension']))
            return false;
        
        return ($ending == $pathInfo['extension']);
    } // end static function isA */
    
    /**
     * Pürfen des Dateitypes
     *
     * @param string $fileName            
     */
    static function getFileType($fileName)
    {
        $pathInfo = pathinfo($fileName);
        
        if (! isset($pathInfo['extension']))
            return null;
        
        return $pathInfo['extension'];
    } // end static function getFileType */
    
    /**
     * Extrahieren des File Pfades
     *
     * @param string $fileName            
     * @return string
     */
    static function getFileFolder($fileName)
    {
        $pathInfo = pathinfo($fileName);
        
        if (! isset($pathInfo['dirname']))
            return null;
        
        return $pathInfo['dirname'];
    } // end static function getFileFolder */
    
    /**
     * Eine Datei erstellen
     * 
     * @param string $path            
     */
    static function touch($path)
    {
        $dir = dirname($path);
        
        if (! Fs::exists($dir))
            Fs::mkdir($dir);
        
        Process::run("touch $path");
    } // end static function touch */
    
    /**
     * Eine Datei erstellen
     * 
     * @param string $path            
     */
    static function touchFileFolder($path)
    {
        $dir = dirname($path);
        
        if (! Fs::exists($dir))
            Fs::mkdir($dir);
    } // end static function touchFileFolder */
    
    /**
     * Den Besitzer einer Datei, oder eines Ordners rekursiv ändern
     *
     * @param string $path            
     * @param string $user            
     */
    static function chown($path, $user)
    {
        Process::run("chown -R $user $path");
    } // end static function chown */
    
    /**
     * Den Besitzer einer Datei, oder eines Ordners rekursiv ändern
     *
     * @param string $path            
     * @param string $user            
     */
    static function chgrp($path, $group)
    {
        Process::run("chgrp -R $group $path");
    } // end static function chgrp */
    
    /**
     * Anpassen der Dateiberechtigungen
     *
     * @param string $path            
     * @param string $level            
     */
    static function chmod($path, $level)
    {
        Process::run("chmod -R $level $path");
    } // end static function chmod */
    
    /**
     * Anpassen der Dateiberechtigungen
     *
     * @param StructPermission $perm            
     * @param ProtocolWriter $protocol            
     */
    static function setPermission($perm, $protocol = null)
    {
        if (! $perm->directory)
            throw new WebExpertException('Missing the directory ' . $perm->directory);
        
        if (! Fs::exists($perm->directory))
            throw new WebExpertException('Directory ' . $perm->directory . ' not exists.');
        
        $cmdRec = '';
        if ($perm->recursive) {
            $cmdRec = ' -R ';
        }
        
        if ($perm->owner && $perm->group) {
            Process::run('chown ' . $cmdRec . $perm->owner . ':' . $perm->group . ' "' . $perm->directory . '"');
            
            if ($protocol)
                $protocol->info('chown ' . $cmdRec . $perm->owner . ':' . $perm->group . ' "' . $perm->directory . '"');
        } elseif ($perm->owner) {
            Process::run('chown ' . $cmdRec . $perm->owner . ' "' . $perm->directory . '"');
            
            if ($protocol)
                $protocol->info('chown ' . $cmdRec . $perm->owner . ' "' . $perm->directory . '"');
        } elseif ($perm->group) {
            Process::run('chgrp ' . $cmdRec . $perm->group . ' "' . $perm->directory . '"');
            
            if ($protocol)
                $protocol->info('chgrp ' . $cmdRec . $perm->group . ' "' . $perm->directory . '"');
        }
        
        if ($perm->accessMask) {
            Process::run('chmod ' . $cmdRec . $perm->accessMask . ' "' . $perm->directory . '"');
            
            if ($protocol)
                $protocol->info('chmod ' . $cmdRec . $perm->accessMask . ' "' . $perm->directory . '"');
        }
    } // end static function setPermission */
    
    /**
     * Anpassen der Dateiberechtigungen
     *
     * @param string $path            
     */
    static function read($path)
    {
        return file_get_contents($path);
    } // end static function read */
    
    /**
     * Schreiben in eine Datei
     *
     * @param string $content            
     * @param string $path            
     */
    static function write($content, $path)
    {
        Fs::touchFileFolder($path);
        return file_put_contents($path, $content);
    } // end static function write */
    
    /**
     *
     * @param string $targetPath            
     * @param string $tplPath            
     * @param array $values            
     */
    static function template($targetPath, $tplPath, $values)
    {
        $folder = dirname($targetPath);
        
        if (! Fs::exists($folder))
            Fs::mkdir($folder);
        
        file_put_contents($targetPath, str_replace(array_keys($values), array_values($values), file_get_contents($tplPath)));
    } // end static function template */
}//end class Fs */