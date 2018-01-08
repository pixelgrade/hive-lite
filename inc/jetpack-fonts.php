<?php
add_filter( 'typekit_add_font_category_rules', function( $category_rules ) {

	TypekitTheme::add_font_category_rule( $category_rules, 'headings',
		'.dropcap,
		.single .entry-content:before,
		.page .entry-content:before',
		array(
			array( 'property' => 'font-family', 'value' => '"Playfair Display", serif' ),
			array( 'property' => 'font-size', 'value' => '275px' ),
		)
	);

	TypekitTheme::add_font_category_rule( $category_rules, 'headings',
		'.site-title',
		array(
			array( 'property' => 'font-family', 'value' => '"Playfair Display", serif' ),
			array( 'property' => 'font-size', 'value' => '40px' ),
		)
	);

	TypekitTheme::add_font_category_rule( $category_rules, 'headings',
		'.site-title',
		array(
			array( 'property' => 'font-size', 'value' => '80px' ),
		),
		array( 'only screen and (min-width: 899px)' )
	);

	TypekitTheme::add_font_category_rule( $category_rules, 'headings',
		'.site-title',
		array(
			array( 'property' => 'font-size', 'value' => '166px' ),
		),
		array( 'only screen and (min-width: 1359px)' )
	);

	TypekitTheme::add_font_category_rule( $category_rules, 'headings',
		'h1',
		array(
			array( 'property' => 'font-family', 'value' => '"Playfair Display", serif' ),
			array( 'property' => 'font-size', 'value' => '61px' ),
		)
	);

	TypekitTheme::add_font_category_rule( $category_rules, 'headings',
		'h2',
		array(
			array( 'property' => 'font-family', 'value' => '"Playfair Display", serif' ),
			array( 'property' => 'font-size', 'value' => '41px' ),
		)
	);

	TypekitTheme::add_font_category_rule( $category_rules, 'headings',
		'.fs-36px,
		.archive__grid .entry-title',
		array(
			array( 'property' => 'font-family', 'value' => '"Playfair Display", serif' ),
			array( 'property' => 'font-size', 'value' => '36px' ),
		)
	);

	TypekitTheme::add_font_category_rule( $category_rules, 'headings',
		'h3',
		array(
			array( 'property' => 'font-family', 'value' => '"Playfair Display", serif' ),
			array( 'property' => 'font-size', 'value' => '27px' ),
		)
	);

	TypekitTheme::add_font_category_rule( $category_rules, 'headings',
		'h4,
		.archive__grid .format-image .entry-title',
		array(
			array( 'property' => 'font-family', 'value' => '"Droid Serif", serif' ),
			array( 'property' => 'font-size', 'value' => '18px' ),
		)
	);

	TypekitTheme::add_font_category_rule( $category_rules, 'headings',
		'h5',
		array(
			array( 'property' => 'font-family', 'value' => '"Droid Serif", serif' ),
			array( 'property' => 'font-size', 'value' => '15px' ),
		)
	);

	TypekitTheme::add_font_category_rule( $category_rules, 'headings',
		'h6',
		array(
			array( 'property' => 'font-family', 'value' => '"Droid Serif", serif' ),
			array( 'property' => 'font-size', 'value' => '12px' ),
		)
	);

	TypekitTheme::add_font_category_rule( $category_rules, 'headings',
		'blockquote',
		array(
			array( 'property' => 'font-family', 'value' => '"Playfair Display", serif' ),
			array( 'property' => 'font-size', 'value' => '32px' ),
		)
	);

	TypekitTheme::add_font_category_rule( $category_rules, 'body-text',
		'body,
		table th,
		table td',
		array(
			array( 'property' => 'font-family', 'value' => '"Droid Serif", serif' ),
		)
	);

	TypekitTheme::add_font_category_rule( $category_rules, 'body-text',
		'.content-quote blockquote,
		.comment-number--dark,
		.add-comment .add-comment__button,
		.comments-area-title .comments-title,
		.comment-reply-title .comments-title,
		.comment-reply-title,
		.comment-navigation .assistive-text,
		.nocomments span',
		array(
			array( 'property' => 'font-family', 'value' => '"Droid Serif", serif' ),
			array( 'property' => 'font-size', 'value' => '28px' ),
		)
	);

	TypekitTheme::add_font_category_rule( $category_rules, 'body-text',
		'blockquote cite',
		array(
			array( 'property' => 'font-family', 'value' => '"Droid Serif", serif' ),
			array( 'property' => 'font-size', 'value' => '27px' ),
		)
	);

	TypekitTheme::add_font_category_rule( $category_rules, 'body-text',
		'.archive__grid .format-status .entry-content,
		.archive__grid .format-status .entry-summary,
		.widget .widget-title',
		array(
			array( 'property' => 'font-family', 'value' => '"Droid Serif", serif' ),
			array( 'property' => 'font-size', 'value' => '24px' ),
		)
	);

	TypekitTheme::add_font_category_rule( $category_rules, 'body-text',
		'.intro',
		array(
			array( 'property' => 'font-family', 'value' => '"Droid Serif", serif' ),
			array( 'property' => 'font-size', 'value' => '23px' ),
		)
	);

	TypekitTheme::add_font_category_rule( $category_rules, 'body-text',
		'site-description',
		array(
			array( 'property' => 'font-family', 'value' => 'sans-serif' ),
			array( 'property' => 'font-size', 'value' => '19px' ),
		)
	);

	TypekitTheme::add_font_category_rule( $category_rules, 'body-text',
		'p,
		blockquote blockquote,
		.pagination a,
		.pagination span,
		.comment-navigation .nav-previous a,
		.comment-navigation .nav-next a,
		div.sharedaddy .sd-social h3.sd-title',
		array(
			array( 'property' => 'font-family', 'value' => '"Droid Serif", serif' ),
			array( 'property' => 'font-size', 'value' => '18px' ),
		)
	);

	TypekitTheme::add_font_category_rule( $category_rules, 'body-text',
		'input,
		textarea,
		.single .entry-meta,
		.page .entry-meta,
		.content-quote cite,
		.comment__author-name a,
		.fs-16px,
		.widget,
		.widget ul,
		.widget_blog_subscription input',
		array(
			array( 'property' => 'font-family', 'value' => '"Droid Serif", serif' ),
			array( 'property' => 'font-size', 'value' => '16px' ),
		)
	);

	TypekitTheme::add_font_category_rule( $category_rules, 'body-text',
		'.fs-14px,
		.entry-meta,
		#recentcomments,
		.widget_blog_subscription,
		.archive__grid .edit-link,
		.comment__author-name,
		.comment__content,
		.comment__links,
		.comment-form-comment textarea,
		.comment-subscription-form textarea,
		.search-form .search-submit',
		array(
			array( 'property' => 'font-family', 'value' => '"Droid Serif", serif' ),
			array( 'property' => 'font-size', 'value' => '14px' ),
		)
	);

	TypekitTheme::add_font_category_rule( $category_rules, 'body-text',
		'.wp-caption-text,
		.fs-13px,
		.widget .post-date,
		.widget_recent_entries .post-date',
		array(
			array( 'property' => 'font-family', 'value' => '"Droid Serif", serif' ),
			array( 'property' => 'font-size', 'value' => '13px' ),
		)
	);

	return $category_rules;
} );
