/**
 * QR CODES plugin for CKEditor 3.x 
 * @Author: Cedric Dugas, http://www.position-absolute.com
 * @Copyright Cakemail
 * @Licence: MIT
 * @version:	 1.0
 */

CKEDITOR.plugins.add('qrcodes',   
  {    
    requires: ['dialog'],
	lang : ['en', 'fr'], 
    init:function(a) { 
	var b="qrcodes";
	var c=a.addCommand(b,new CKEDITOR.dialogCommand(b));
		c.modes={wysiwyg:1,source:0};
		c.canUndo=false;
	a.ui.addButton("qrcodes",{
					label:a.lang.qrcodes.title,
					command:b,
					icon:this.path+"qrcode.jpg"
	});
	CKEDITOR.dialog.add(b,this.path+"dialogs/qrcode.js")}
});