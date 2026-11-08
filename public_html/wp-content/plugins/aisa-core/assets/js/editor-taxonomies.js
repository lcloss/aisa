/* global wp */
wp.domReady( function () {
	'use strict';
	// The native PHP select boxes replace Gutenberg's multiple-term panels.
	[ 'aisa_valencia', 'aisa_tipo_documento' ].forEach( function ( taxonomy ) {
		wp.data.dispatch( 'core/editor' ).removeEditorPanel( 'taxonomy-panel-' + taxonomy );
	} );
} );
