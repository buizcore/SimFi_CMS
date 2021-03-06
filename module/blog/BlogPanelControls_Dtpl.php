<?php


/**
 * @package com.BuizCore
 * @subpackage SimFi
 */
class BlogPanelControls_Dtpl extends Dyntemplate
{


  /**
   * @param TemplateWorkarea_Cms $view
   */
  public function renderControls($view)
  {


    $code = <<<HTML
    
  <div 
    class="m-button tip-r" 
    title="Blogeintrag mit Bild" ><button id="bc-new-text_image"><i class="fa fa-picture-o fa-2x" ></i></button></div>
  <div 
    class="m-button tip-r" 
    title="Nur Text Blogeintrag" ><button id="bc-new-text_only"><i class="fa fa-align-justify fa-2x" ></i></button></div>

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

$('#bc-new-text_image').on('click',function(){

  var tpl = $('#bc-new-text_image-tpl').text();
  tpl = tpl.replace(/{{id}}/g, ''+boxCounter);
  ++boxCounter;

  $('#blog-editor-anchor').after(tpl);
  initEditor();
});

$('#bc-new-text_only').on('click',function(){

  var tpl = $('#bc-new-text_only-tpl').text();
  tpl = tpl.replace(/{{id}}/g, ''+boxCounter);
  ++boxCounter;

  $('#blog-editor-anchor').after(tpl);
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

  $.ajax( {
    type:'POST',
    url: formNode.attr('action')+"&ajax=true",
    data: data,
    success: function(data, textStatus, jqXHR){
      if(buttonNode.attr('data-callback')){
        window.bc_callbacks[buttonNode.attr('data-callback')](buttonNode.parentX('section'),data);
      }
    }
  });


  jSuccess('Der Blogeintra wurden gespeichert',{
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
