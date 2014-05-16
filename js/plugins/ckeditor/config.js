/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
function getFileName() {
	//this gets the full url
	var url = document.location.href;
	//this removes the anchor at the end, if there is one
	url = url.substring(0, (url.indexOf("#") == -1) ? url.length : url.indexOf("#"));
	//this removes the query after the file name, if there is one
	url = url.substring(0, (url.indexOf("?") == -1) ? url.length : url.indexOf("?"));
	//this removes everything before the last slash in the path
	url = url.substring(url.lastIndexOf("/") + 1, url.length);
	//return
	return url;
}
// Getting the file name and seeing if its french or english
var str=getFileName();
var patt1 = /eng/gi;
var patt2 = /fra/gi;

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	if (str.match(patt2) == "fra") {
		config.language = 'fr';
	}
	if (str.match(patt2) == "fra") {
		config.scayt_sLang = 'fr_CA';
	} else {
		config.scayt_sLang = 'en_CA';
	}
	config.uiColor = '#7a7777';
	config.enterMode = CKEDITOR.ENTER_BR;
	config.shiftEnterMode = CKEDITOR.ENTER_P;
	config.contentsCss = ['/css/new_template/base.css', '/css/new_template/common.css', '/css/new_template/elements.css', '/css/new_template/hcintranet.css', '/css/new_template/institution.css', '/css/new_template/theme-terrafirma2-ckeditor.css'];
	config.contentsLangDirection = 'ltr';
	config.colorButton_enableMore = true;
	config.baseHref = 'http://intranet.hc-sc.gc.ca/';
	config.font_names = 'Verdana';
	config.forcePasteAsPlainText = false;
	config.removePlugins = 'elementspath,flash,smiley,iframe,pagebreak,specialchar,about,showblocks,blockquote';
	config.resize_dir = 'vertical';
};
