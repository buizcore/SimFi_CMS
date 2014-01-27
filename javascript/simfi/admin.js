
window.bc_callbacks = {};

$(document).ready(function(){
    
    $('#open-metas').on('click',function(){
        $('#simfi-meta-editor').toggle();
        $(this).parent().toggleClass('active');
    });
    
    $('#btn-simfi-settings').on('click',function(){
        $('#simfi-settings-editor').toggle();
        $(this).parent().toggleClass('active');
    });

    $('#menu-trigger').on('click',function(){
        $('.contr-submenu').css('display','');
    });

    $('div.contr-submenu').find('input').on('click',function(){
        $('.contr-submenu').css('display','block');
    });

    var initEditor = function(){

		tinymce.init({
		    selector: ".ec",
		    theme: "modern",
		    add_unload_trigger: false,
		    schema: "html5",
		    inline: true,
		    plugins: [
		      "link lists"
		    ],
		    forced_root_block : false,
		    language : sfSetting.lang,
		    toolbar: "undo redo  | bold italic link | bullist numlist ", //alignleft aligncenter alignright alignjustify |
		    statusbar: false,
		    menubar : false
		});

        $('.ec').addClass('bc-e-text');

        tinymce.init({
          selector: ".ecl",
          theme: "modern",
          add_unload_trigger: false,
          schema: "html5",
          inline: true,
          forced_root_block : false,
          language : sfSetting.lang,
          toolbar: "undo redo",
          statusbar: false,
          menubar : false
        });

        $('.ecl').addClass('bc-e-text');

        // upload
        $('.ajax').each(function(){

          var jNode = $(this);
          jNode.removeClass('ajax');

          jNode.on('click',function(){
            $.ajax({
              'url':jNode.attr('href'),
              'data': {'ajax':'true'},
              'success':function(data, textStatus, jqXHR){

                if ('string' == typeof data) {
                  data = $.parseJSON(data);
                }

                if (jNode.attr('data-callback')) {
                  window.bc_callbacks[jNode.attr('data-callback')](jNode,data);
                }
              }
            });
            return false;
          });

        });

        // upload
        $('.dz').each(function(){

          var jNode = $(this);
          jNode.removeClass('dz');

          jNode.dropzone({
            url: sfSetting.upload,
            uploadMultiple: false,
            paramName: jNode.attr('data-p_name')||'image',
            clickable: false,
            //forceFallback: true,
            addRemoveLinks: true,
            maxThumbnailFilesize:5,
            previewsContainer: '#simfi-upload-preview',
            drop:function(){
                $('#simfi-upload-preview').show();  
            },
            init: function() {
              this.on("processing", function(file) {

                if(jNode.attr('data-params')){
                  this.options.url = sfSetting.upload+'?ajax=true&'+jNode.attr('data-params');
                } else {
                  this.options.url = sfSetting.upload+'?ajax=true';
                }

                console.log('upload url '+this.options.url );
              });
            },
            success:function(file, response){
                
              var jsonResp = $.parseJSON(response);
              setNewImage(jNode,jsonResp);
            	
              if (jNode.attr('data-callback')) {
                window.bc_callbacks[jNode.attr('data-callback')](jNode,jsonResp);
              }
              //$R.handelSuccess($S.parseXML(response));
            }
          });

          jNode.find('.dz-default.dz-message').remove();
        });

        /*  dropdown */
        $('.dzl').each(function(){

          jNode = $(this);
          jNode.removeClass('dzl');

          jNode.dropzone({
            url: sfSetting.upload+'?ajax=true',
            uploadMultiple: true,
            paramName: jNode.attr('data-p_name')||'image',
            clickable: this,
            //forceFallback: true,
            addRemoveLinks: true,
            maxThumbnailFilesize:5,
            previewsContainer: '#simfi-upload-preview',
            drop:function(){
              $('#simfi-upload-preview').show();  
            },
            success:function(file, response){

              //$R.handelSuccess($S.parseXML(response));
            }
          });

          jNode.find('.dz-default.dz-message').remove();
        });
        
        /* Editierbare Bilder */
        $('.crop').each(function(){

          var jNode = $(this);
          
          var pos = {},
          imgWidth = 0,
          imgHeight = 0,
          jcrop_api = null,
          
          removeCrop = function(){
              $('#panel-crop-controls').remove();
              $('#main-content').off('click',clickCrop);
              jcrop_api.release();
          },          
          clickCrop = function(evt){
              
             var clckTarget = $(evt.target);
              
             if( !clckTarget.parentX(jNode.next()) && !clckTarget.parentX('div#panel-crop-controls') && !clckTarget.is('div#panel-crop-controls') ){
                 removeCrop();
             } 
          };
          
          jNode.Jcrop({
              'onSelect': function(cords){
                  
                  if($('#panel-crop-controls').is('#panel-crop-controls')){
                      $('#inp-img-crops-x').val(cords.x);
                      $('#inp-img-crops-y').val(cords.y);
                      $('#inp-img-crops-x2').val(cords.x2);
                      $('#inp-img-crops-y2').val(cords.y2);
                      return;
                  }

                  pos = jNode.next().offset();
                  imgWidth = jNode.outerWidth();
                  imgHeight = jNode.outerHeight();
                  
                  
                  var tpl = $('#tpl-grop-menu').html();
                  tpl = Handlebars.compile(tpl);
                  
                  var data = {
                    'top':(pos.top+imgHeight),
                    'left':pos.left,
                    'width': imgWidth,
                    'imgHeight': imgHeight,
                    'imgWidth': imgWidth,
                    'alt': jNode.attr('alt')
                  }
    
                  $('#main-content').append(tpl(data));
                  
                  $('#inp-img-crops-x').val(cords.x);
                  $('#inp-img-crops-y').val(cords.y);
                  $('#inp-img-crops-x2').val(cords.x2);
                  $('#inp-img-crops-y2').val(cords.y2);
                  
                  $('#main-content').on('click',clickCrop);
                  
              },
              'onRelease': function(){
                  removeCrop();
              }
          },function(){
              jcrop_api = this;
          });

        });

      };


      initEditor();

      // save the page
      $('#save-page').on('click',function(){

        var data = {};
        $('.bc-e-text').each(function(){
          data[$(this).attr('data-key')] = $(this).html();
        });

        $('.bc-e-list').each(function(){

          var listKey = $(this).attr('data-key');
          data[listKey] = [];

          $(this).find('li').each(function(){
            data[listKey].push($(this).html());
          });

        });

        $.post( sfSetting.save+"?ajax=true", data );
        reportSuccess('Die Seite wurden gespeichert');

    });
      
    // save the page
    $('#btn-save-meta-data').on('click',function(){

        var data = $('#form-meta-data').find(':input').serialize();
        
        $.post( sfSetting.saveMeta+"?ajax=true", data );
        reportSuccess('Die Metadaten wurden gespeichert');
        
        $('#simfi-meta-editor').hide();
        
        return false;
    });
    

    function reportSuccess(msg){
        jSuccess(msg,{
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
            onClosed : function(){},
            onCompleted : function(){}
      });
    }
    
    function setNewImage(jNode,jsonResp){
        jNode.attr('src',jsonResp.new_src);
    }

    
    window.setInterval(function(){$('.mce-resizehandle').hide();},300);
    
    window.setInterval(function(){
        $('#simfi-upload-preview .dz-preview').each(function(){
            var uplPrev = $(this);
            if(uplPrev.find('.dz-upload')){
                
            }
            uplPrev.fadeOut(300);
            uplPrev.remove();
        });
        
        if(!$('#simfi-upload-preview .dz-preview').length){
            $('#simfi-upload-preview').fadeOut(500);
        }
        
    },6000);

    
});