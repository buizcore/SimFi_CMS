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

      if (!file_exists($fileName)) {
        throw new WebExpertException('Versucht ein nichtvorhandenes Bild zu resizen');
      }

      $errorpic = '';

      $imgdata = getimagesize ($fileName);
      $org_width = $imgdata[0];
      $org_height = $imgdata[1];
      $type = $imgdata[2];

      switch ($type) {
        case 1 :{
          if (!$im = ImageCreateFromGif ($fileName)) {
            throw new WebExpertException("Konnte das Bild nicht erstellen");
          }
          break;
        } // ENDE CASE

        case 2 :{
          if (!$im = ImageCreateFromJPEG($fileName)) {
            throw new WebExpertException("Konnte das Bild nicht erstellen");
          }
          break;
        } // ENDE CASE

        case 3 :{
          if (!$im = ImageCreateFromPNG($fileName)) {
            throw new WebExpertException("Konnte das Bild nicht erstellen");
          }
          break;
        } // ENDE CASE

        // Erstellen eines eigenen Vorschaubilds
        default:{
          // Standartbild hinkopieren
          if (!$im = ImageCreateFromJPEG($errorpic)) {
            throw new WebExpertException("Konnte das Bild nicht erstellen");
          }
          // Neueinlesen der benötigten Daten
          $imgdata = getimagesize ($errorpic);
          $org_width = $imgdata[0];
          $org_height = $imgdata[1];
        }

      } // ENDE SWITCH

      // Errechnen der neuen Größe
      if ($org_width > $org_height) {

        $relation = $org_width / $org_height;
        $newWidth = $this->maxWidth;
        $newHeight = round(($newWidth / $relation)  ) ;
      } else {

        $relation = $org_height / $org_width ;
        $newHeight = $this->maxHeight;
        $newWidth = round(($newHeight / $relation)  ) ;
      }

      // neugenerieren des THUMBS
      $thumb = imagecreatetruecolor($newWidth, $newHeight);

      imagecopyresampled(
        $thumb,
        $im,
        0,0,
        0,0,
        $newWidth,$newHeight,
        $org_width,$org_height
      );

      if (!imagejpeg($thumb, $newName, 95)) {
        throw new WebExpertException('Failed to create '.$newName);
      }

      return true;

    } catch (WebExpertException $e) {

      return false;
    }

  }//end public function genThumb

}// end class LibImageThumbGd
