/**
 * Hive Customizer JavaScript - keeps things nicer for all
 * v 1.0.0
 */

/**
 * Some AJAX powered controls
 * jQuery is available
 */
(function( $ ) {

	// Change site title and description when they are typed
	wp.customize( 'blogname', function( value ) {
		value.bind( function( text ) {
			$( '.site-title a' ).text( text );
		} );
	} );

	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( text ) {
			$( '.site-description-text' ).text( text );
		} );
	} );

	// Update the site title size class
	wp.customize( 'hive_title_size', function( value ) {
		value.bind( function( sizeClass ) {
			//remove the previous size class
			$( '.site-title' ).removeClass( function (index, css) {
				return (css.match (/(^|\s)site-title--\S+/g) || []).join(' ');
			});

			//add the new class
			$( '.site-title' ).addClass( sizeClass );

		} );
	} );
})( jQuery );