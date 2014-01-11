<?php


/**
 * 
 * @subpackage web_expert.cms
 */
class BlogPanelControls_Dtpl extends Dyntemplate
{


  /**
   * @param TemplateWorkarea_Cms $view
   */
  public function renderControls($view)
  {


    $code = <<<HTML
  <div class="block" >
    <label>Neuen Blogeintrag anlegen:&nbsp;&nbsp;</label>
    <a id="bc-new-text_image">Mit Bild <i class="fa fa-picture-o" ></i></a> |
    <a id="bc-new-text_only">Nur Text <i class="fa fa-list-alt" ></i></a>
  </div>
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
