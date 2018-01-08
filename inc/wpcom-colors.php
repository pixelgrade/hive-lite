<?php

/* Custom Colors: Hive */

add_color_rule( 'bg', '#444444', array(
) );

add_color_rule( 'txt', '#444444', array(
) );

add_color_rule( 'link', '#afafaf', array(

	//Contrast against white background
    array( 'a, .comment-reply-link', 'color', '#ffffff' ),
    
    //Contrast with accent color
    array( '.sticky a', 'color', 'fg1' ),
),
__( 'Links' ) );

add_color_rule( 'fg1', '#ffeb00', array(
	// No contrast
    array( '.highlight:before, .archive__grid .accent-box, .sticky:not(.format-quote):after, .widget_blog_subscription input[type="submit"]', 'background-color' ),
    array( '.widget_blog_subscription input[type="submit"]', 'border-color' ),
	
	//Contrast with white background
	array( '.content-quote blockquote:before, .content-quote blockquote:before, .widget a:hover, .content-quote blockquote:after', 'color', '#ffffff' ),
),
__( 'Main Accent' ) );

add_color_rule( 'fg2', '#444444', array(
) );


//Extra rules

add_color_rule( 'extra', '#444444', array(
	array( '.sticky:not(.format-quote)', 'color', 'fg1' ),
) );

add_color_rule( 'extra', '#3d3e40', array(
	array( '.sticky:not(.format-quote) a', 'color', 'fg1' ),
) );

add_color_rule( 'extra', '#ffffff', array(
	array( '.widget_blog_subscription input[type="submit"]', 'color', 'fg1' ),
) );

add_color_rule( 'extra', '#171617', array(
	array( '.sticky:not(.format-quote) h1, .sticky:not(.format-quote) h2, .sticky:not(.format-quote) h3, .sticky:not(.format-quote) blockquote, .sticky:not(.format-quote) .dropcap', 'color', 'fg1' ),
) );


//Extra CSS

function hive_extra_css() { ?>
	.content-quote blockquote:after {
		-webkit-border-image: none;
		-webkit-border-image: none;
				border-image: none;
				border-image: none;
	}
<?php }
add_theme_support( 'custom_colors_extra_css', 'hive_extra_css' );
