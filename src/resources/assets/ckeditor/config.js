/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */
var base_url = assetURL.replace("public/", "");
CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.toolbar = [
        {
            name: 'document',
            /*items: ['Source', '-', 'Save', 'NewPage', 'DocProps', 'Preview', 'Print', '-', 'Templates']*/
            items: ['Source', '-', 'Templates']
        },
        {
            name: 'styles',
            items: ['Styles', 'Format','Font','FontSize']
            //items: ['Styles','Format','Font','FontSize' ]
        },
        {   name: 'colors',      
            items : [ 'TextColor','BGColor' ] 
        },
        {
            name: 'basicstyles',
            items: ['Bold', 'Italic', 'Strike', '-', 'RemoveFormat']
        },
        
        /*{
            name: 'editing',
            items: ['Find', 'Replace', '-', 'SelectAll', '-', 'Scayt']
        },*/
        {
            name: 'insert',
            /*items: ['Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe']*/
            items: ['Image', 'Table', 'HorizontalRule']
        },
        {
            name: 'paragraph',
            items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote']
        },
        {
            name: 'clipboard',
            items: ['Undo', 'Redo', 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-']
        },
        {
            name: 'paragraph',
            items: ['Subscript', 'Superscript', 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv',
                '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl'
            ]
        },
        {
            name: 'links',
            items: ['Link', 'Unlink', 'Anchor','Youtube']
        },
        {
            name: 'tools',
            items: ['Maximize', '-']
        },

    ];

        config.filebrowserBrowseUrl = base_url + '/ckfinder/ckfinder.html';
        config.filebrowserImageBrowseUrl = base_url + '/ckfinder/ckfinder.html?type=Images';
        config.filebrowserUploadUrl = base_url + '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
        config.filebrowserImageUploadUrl = base_url + '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';

    config.enterMode = CKEDITOR.ENTER_BR;
    config.autoParagraph = false;
    config.entities = false;
    config.extraPlugins = 'youtube';
};
