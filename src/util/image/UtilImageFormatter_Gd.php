<?php

/**
 * class LibImageThumbGd
 *
 * @package com.BuizCore
 * @subpackage SimFi
 */
class UtilImageFormatter_Gd extends UtilImageFormatterAdapter
{

    /**
     */
    public function resize($fileName, $newName = null, $maxWidth = null, $maxHeight = null)
    {
        
        if ($maxWidth) {
            $this->maxWidth = $maxWidth;
        }
        
        if ($maxHeight) {
            $this->maxHeight = $maxHeight;
        }
        
        try {
            
            $imgData = $this->openImage($fileName);
              
            // Errechnen der neuen Größe
            if ($imgData->width > $imgData->height) {
                
                $relation = $imgData->width / $imgData->height;
                $newWidth = $this->maxWidth;
                $newHeight = round(($newWidth / $relation));
            } else {
                
                $relation = $imgData->height / $imgData->width;
                $newHeight = $this->maxHeight;
                $newWidth = round(($newHeight / $relation));
            }
            
            // neugenerieren des THUMBS
            $thumb = imagecreatetruecolor($newWidth, $newHeight);
            
            imagecopyresampled($thumb, $im, 0, 0, 0, 0, $newWidth, $newHeight, $imgData->width, $imgData->height);
            
            $path = pathinfo($newName);
            
            if (!file_exists($path['dirname'])) {
                Fs::mkdir($path['dirname'],'0755');
            }
            
            if (! imagejpeg($thumb, $newName, 95)) {
                throw new SimFiException('Failed to create ' . $newName);
            }
            
            return true;
            
        } catch (SimFiException $e) {
            
            return false;
        }
    
    } // end public function resize
    
    
    /**
     * 
     * @param unknown $fileName
     * @param string $posX
     * @param string $posY
     * @param string $width
     * @param string $height
     * @param string $newName
     * @throws SimFiException
     * @return boolean
     */
    public function crop($fileName, $posX = null, $posY = null, $width = null, $height = null, $newName = null  )
    {

        
        if(!$newName)
            $newName = $fileName;
    
        try {
    
            if (! file_exists($fileName)) {
                throw new SimFiException('Versucht ein nichtvorhandenes Bild zu resizen');
            }
    
            $errorpic = '';

            $imgdata = getimagesize($fileName);
            $org_width = $imgdata[0];
            $org_height = $imgdata[1];
            $type = $imgdata[2];

            switch ($type) {
                case 1: {
                    if (! $im = ImageCreateFromGif($fileName)) {
                        throw new SimFiException("Konnte das Bild nicht erstellen");
                    }
                    break;
                } // ENDE CASE
                case 2: {
                    if (! $im = ImageCreateFromJPEG($fileName)) {
                        throw new SimFiException("Konnte das Bild nicht erstellen");
                    }
                    break;
                } // ENDE CASE
    
                case 3: {
                    if (! $im = ImageCreateFromPNG($fileName)) {
                        throw new SimFiException("Konnte das Bild nicht erstellen");
                    }
                    break;
                } // ENDE CASE
    
                default: {
                    
                    throw new SimFiException('Bild Format nicht unterstüzt');
                }
            } // ENDE SWITCH
    
            // Errechnen der neuen Größe
            if ($org_width > $org_height) {
    
                $relation = $org_width / $org_height;
                $newWidth = $this->maxWidth;
                $newHeight = round(($newWidth / $relation));
            } else {
    
                $relation = $org_height / $org_width;
                $newHeight = $this->maxHeight;
                $newWidth = round(($newHeight / $relation));
            }
    
            // neugenerieren des THUMBS
            $thumb = imagecreatetruecolor($newWidth, $newHeight);
    
            imagecopyresampled($thumb, $im, 0, 0, 0, 0, $newWidth, $newHeight, $org_width, $org_height);
    
            if (! imagejpeg($thumb, $newName, 95)) {
                throw new SimFiException('Failed to create ' . $newName);
            }

            return true;
        } catch (SimFiException $e) {

            return false;
        }

    } // end public function crop */
    
    
    /**
     * Diese Funktion erzeugt ein Bild mit Exakt der angegebenen
     * Breite und Höhe. Ein allfälliger Überhang wird dabei
     * gleichmässig auf beiden Seiten abgeschnitten.
     *
     * @param string $fileName
     * @param float $width
     * @param float $height
     * @param string $newName
     */
    public function resizeCropOverflow($fileName, $width, $height, $newName = null) 
    {
        
        if (!$newName)
            $newName = $fileName;
        
        $imgData = $this->openImage($fileName);

        //Seitenverhältnis neu/alt bestimmen
        $scaleX = (float)$width / $imgData->width;
        $scaleY = (float)$height / $imgData->height;
    
        //Grösseres Seitenverhältnis zählt.
        $scale = max($scaleX, $scaleY);
    
        $dstW = $width;
        $dstH = $height;
        $dstX = $dstY = 0;
    
        //neue grösse berechnen, damit mindestens höhe bzw. breite korrekt ist
        $dstW = (int)($scale * $imgData->width + 0.5);
        $dstH = (int)($scale * $imgData->height + 0.5);
    
        //zielposition bestimmen
        $dstX = (int)(0.5 * ($width - $dstW));
        $dstY = (int)(0.5 * ($height - $dstH));
    
        //bild auf neue zielgrösse bestimmen. dabei ist erst eine seite korrekt
        $new_image = imagecreatetruecolor($dstW, $dstH);
        imagecopyresampled($new_image, $imgData->res, 0, 0, 0, 0, $dstW, $dstH, $src_w, $src_h);
    
        //prüfen ob abgeschnitten werden muss. falls ja neues bild mit definitiver grösse erstellen und altes verschoben einkopieren.
        if ($dstW != $width || $dstH != $height || $dstX != 0 || $dstY != 0) {
            $final_image = imagecreatetruecolor($width, $height);
            imagecopyresampled($final_image, $new_image, 0, 0, -$dstX, -$dstY, $width, $height, $width, $height);
            $new_image = $final_image;
        }
        $this->image = $new_image;
    }
    
    /**
     * @param string $fileName
     * @param string $fallbackImg Bild welches verwendet werden soll wenn sich das Bild mit GD nicht öffnen lässt
     * @return UtilImageData
     * @throws SimFiException
     */
    public function openImage($fileName, $fallbackImg = null)
    {
        
        $imgData = new UtilImageData();
        
        $imgData->imagePath = $fileName;

        if (!file_exists($fileName)) {
            throw new SimFiException('Versucht ein nicht vorhandene Bild zu öffnen');
        }
    
        $errorpic = '';
    
        $imgdata = getimagesize($fileName);
        $imgData->width = $imgdata[0];
        $imgData->height = $imgdata[1];
        $imgData->type = $imgdata[2];
    
        switch ($imgData->type) {
        	case 1:{
    	        if (!$imgData->res = ImageCreateFromGif($fileName)) {
    	            throw new SimFiException("Konnte das Bild nicht erstellen");
    	        }
    	        break;
    	    } // ENDE CASE
    
        	case 2:{
    	        if (!$imgData->res = ImageCreateFromJPEG($fileName)) {
    	            throw new SimFiException("Konnte das Bild nicht erstellen");
    	        }
    	        break;
    	    } // ENDE CASE
    
        	case 3:{
    	        if (!$imgData->res = ImageCreateFromPNG($fileName)) {
    	            throw new SimFiException("Konnte das Bild nicht erstellen");
    	        }
    	        break;
    	    } // ENDE CASE
    
        	    // Erstellen eines eigenen Vorschaubilds
        	default:{
    	        // Standartbild hinkopieren
    	        if (!$im = ImageCreateFromJPEG($errorpic)) {
    	            throw new SimFiException("Konnte das Bild nicht erstellen");
    	        }
    	        // Neueinlesen der benötigten Daten
    	        $imgdata = getimagesize($errorpic);
                $imgData->width = $imgdata[0];
                $imgData->height = $imgdata[1];
                $imgData->type = $imgdata[2];
    	    }
        } // ENDE SWITCH
        
        return $imgData;
      
    }//end public function openImage
    
}// end class UtilImageFormatter_Gd
