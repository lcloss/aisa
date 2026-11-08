/* global wp */
( function () {
	'use strict';
	document.addEventListener( 'DOMContentLoaded', function () {
		var button = document.getElementById( 'aisa-select-file' );
		if ( ! button ) { return; }
		button.addEventListener( 'click', function () {
			var frame = wp.media( { title: wp.i18n.__( 'Escolher ficheiro', 'aisa' ), multiple: false, library: { type: [ 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' ] } } );
			frame.on( 'select', function () {
				var file = frame.state().get( 'selection' ).first().toJSON();
				document.getElementById( '_aisa_doc_ficheiro' ).value = file.id;
				document.getElementById( 'aisa-file-name' ).textContent = file.filename;
			} );
			frame.open();
		} );
	} );
}() );
