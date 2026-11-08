const assert = require( 'node:assert/strict' );
const fs = require( 'node:fs' );
const vm = require( 'node:vm' );
const listeners = {};
const window = {};
vm.runInNewContext( fs.readFileSync( __dirname + '/../assets/js/gtm-forms.js', 'utf8' ), {
	window,
	document: { addEventListener: ( name, callback ) => { listeners[ name ] = callback; } }
} );
assert.deepEqual( Object.keys( listeners ), [ 'wpcf7mailsent' ] );
function submit( classes, dataset = {}, direct = false ) {
	const form = { dataset, classList: { contains: id => classes.includes( id ) }, matches: () => true, querySelector: () => null };
	listeners.wpcf7mailsent( { target: direct ? form : { matches: () => false, querySelector: () => form }, detail: { inputs: [ { value: 'private@example.test' } ] } } );
}
submit( [ 'wpcf7-form', 'form-voluntariado' ] );
submit( [ 'form-contacto' ], {}, true );
submit( [], { formId: 'form-contacto' } );
submit( [ 'unrelated' ] );
submit( [], { formId: 'private@example.test' } );
listeners.wpcf7mailsent( { target: null } );
listeners.wpcf7mailsent( { target: { matches: () => false, querySelector: () => null } } );
assert.deepEqual( JSON.parse( JSON.stringify( window.dataLayer ) ), [
	{ event: 'form_submit', form_id: 'form-voluntariado' },
	{ event: 'form_submit', form_id: 'form-contacto' },
	{ event: 'form_submit', form_id: 'form-contacto' }
] );
console.log( 'PASS CF7 success listener: wrapper/form targets, class/data-form-id, unknown forms ignored, no field data.' );
