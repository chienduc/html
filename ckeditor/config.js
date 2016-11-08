/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.plugins.addExternal('fmath_formula', 'plugins/fmath_formula/', 'plugin.js');

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.extraPlugins='backgrounds,syntaxhighlight,video,youtube,jwplayer,fmath_formula,qrcodes,smallerselection,googlemaps';//serverpreview';
	config.toolbar_Full.push(['Code','Video','Youtube','jwplayer','fmath_formula','GoogleMaps','qrcodes']);
	config.serverPreview_Url='/preview.php';
	
	// File Manager
	config.filebrowserBrowseUrl = '/plugins/filemanager/browse.php?type=files';
	config.filebrowserImageBrowseUrl = '/plugins/filemanager/browse.php?type=images';
	config.filebrowserFlashBrowseUrl = '/plugins/filemanager/browse.php?type=flash';
	config.filebrowserUploadUrl = '/plugins/filemanager/upload.php?type=files';
	config.filebrowserImageUploadUrl = '/plugins/filemanager/upload.php?type=images';
	config.filebrowserFlashUploadUrl = '/plugins/filemanager/upload.php?type=flash';
};