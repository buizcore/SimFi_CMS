<?php


/**
 * @package com.BuizCore
 * @subpackage SimFi
 */
class GalleryPanelControls_Dtpl extends Dyntemplate
{


  /**
   * @param TemplateWorkarea_Cms $view
   */
  public function renderControls($view)
  {


    $code = <<<HTML
  <div 
    class="m-button tip-r" 
    title="Ein neues Bild hinzufügen" ><button id="bc-new-image"><i class="fa fa-picture-o fa-2x" ></i></button></div>
HTML;

    return $code;

  }

  /**
   * @param TemplateWorkarea_Cms $view
   */
  public function renderJs($view)
  {

?>
<script>
var boxCounter = 0;

$('#bc-new-image').on('click',function(){

  var tpl = $('#bc-new-image-tpl').text();
  tpl = tpl.replace(/{{id}}/g, ''+boxCounter);
  ++boxCounter;

  $('#image-gallery>ul').prepend(tpl);
  initEditor();
});


$( document ).on( "click", "button.save-entry",function(){

  var buttonNode = $(this);
  var formNode = buttonNode.parentX('form');

  var data = {};
  formNode.find('.ec,.ecl').each(function(){
    data[$(this).attr('data-key')] = $(this).html();
  });
  formNode.find('.dzi').each(function(){
    data[$(this).attr('data-key')] = $(this).attr('src');
  });
  formNode.find('input.val').each(function(){
    data[$(this).attr('name')] = $(this).val();
  });

  $.ajax( {
    type:'POST',
    url: formNode.attr('action')+"&ajax=true",
    data: data,
    success: function(data, textStatus, jqXHR){
      if(buttonNode.attr('data-callback')){
        window.bc_callbacks[buttonNode.attr('data-callback')](buttonNode.parentX('li'),data);
      }
    }
  });


  jSuccess('Das Bild wurden gespeichert',{
	  autoHide : true, // added in v2.0
	  clickOverlay : false, // added in v2.0
	  MinWidth : 250,
	  TimeShown : 1500,
	  ShowTimeEffect : 200,
	  HideTimeEffect : 200,
	  LongTrip :20,
	  HorizontalPosition : 'left',
	  VerticalPosition : 'bottom',
	  ShowOverlay : true,
	  ColorOverlay : '#000',
	  OpacityOverlay : 0.5,
	  onClosed : function(){ // added in v2.0
		},
	  onCompleted : function(){ // added in v2.0
		}
	});

  return false;
});


</script>
<?php

  }


}//end class BlogPanelControls_Dtpl */
