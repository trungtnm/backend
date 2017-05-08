/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

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

	config.enterMode = CKEDITOR.ENTER_BR;
    config.autoParagraph = false;
    config.entities = false;
    config.extraPlugins = 'image2,widget,lineutils,widgetselection,embed,notificationaggregator,notification,toolbar,button';

	config.image2_alignClasses = [ 'text-left', 'text-center', 'text-right' ];
	config.image2_captionedClass = 'image-captioned';

};
