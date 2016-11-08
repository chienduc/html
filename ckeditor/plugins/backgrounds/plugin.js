CKEDITOR.plugins.add('backgrounds',{lang:['en','es'],init:function(editor){}});CKEDITOR.on('dialogDefinition',function(ev){var dialogName=ev.data.name,dialogDefinition=ev.data.definition,editor=ev.editor,tabName='';if(dialogName=='table'||dialogName=='tableProperties')tabName='advanced';if(dialogName=='cellProperties')tabName='info';if(tabName=='')return;var tab=dialogDefinition.getContents(tabName);var textInput={type:'text',label:editor.lang.backgrounds.label,id:'background',setup:function(selectedElement){this.setValue(selectedElement.getAttribute('background'))},commit:function(data,selectedElement){var element=selectedElement||data,value=this.getValue();if(value)element.setAttribute('background',value);else element.removeAttribute('background')}};var browseButton={type:'button',id:'browse',hidden:'true',filebrowser:{action:'Browse',target:tabName+':background',url:editor.config.filebrowserImageBrowseUrl||editor.config.filebrowserBrowseUrl},label:editor.lang.common.browseServer};if(tabName=='advanced'){tab.add(textInput);tab.add(browseButton)}else{browseButton.style='display:inline-block;margin-top:10px;';tab.add({type:'hbox',widths:['','100px'],children:[textInput,browseButton]})}},null,null,9);CKEDITOR.plugins.setLang('backgrounds','en',{backgrounds:{label:'Background image'}});CKEDITOR.plugins.setLang('backgrounds','es',{backgrounds:{label:'Imagen de fondo'}});