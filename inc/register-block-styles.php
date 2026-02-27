<?php
/**
 * Block styles.
 *
 * @package ellura-collections
 * @since 1.0.0
 */

/**
 * Register block styles
 *
 * @since 1.0.0
 *
 * @return void
 */
function ellura_collections_register_block_styles() {

	register_block_style( // phpcs:ignore WPThemeReview.PluginTerritory.ForbiddenFunctions.editor_blocks_register_block_style
		'core/button',
		array(
			'name'  => 'ellura-collections-flat-button',
			'label' => __( 'Flat button', 'ellura-collections' ),
		)
	);

	register_block_style( // phpcs:ignore WPThemeReview.PluginTerritory.ForbiddenFunctions.editor_blocks_register_block_style
		'core/list',
		array(
			'name'  => 'ellura-collections-list-underline',
			'label' => __( 'Underlined list items', 'ellura-collections' ),
		)
	);

	register_block_style( // phpcs:ignore WPThemeReview.PluginTerritory.ForbiddenFunctions.editor_blocks_register_block_style
		'core/group',
		array(
			'name'  => 'ellura-collections-box-shadow',
			'label' => __( 'Box shadow', 'ellura-collections' ),
		)
	);

	register_block_style( // phpcs:ignore WPThemeReview.PluginTerritory.ForbiddenFunctions.editor_blocks_register_block_style
		'core/column',
		array(
			'name'  => 'ellura-collections-box-shadow',
			'label' => __( 'Box shadow', 'ellura-collections' ),
		)
	);

	register_block_style( // phpcs:ignore WPThemeReview.PluginTerritory.ForbiddenFunctions.editor_blocks_register_block_style
		'core/columns',
		array(
			'name'  => 'ellura-collections-box-shadow',
			'label' => __( 'Box shadow', 'ellura-collections' ),
		)
	);

	register_block_style( // phpcs:ignore WPThemeReview.PluginTerritory.ForbiddenFunctions.editor_blocks_register_block_style
		'core/details',
		array(
			'name'  => 'ellura-collections-plus',
			'label' => __( 'Plus & minus', 'ellura-collections' ),
		)
	);
}
add_action( 'init', 'ellura_collections_register_block_styles' );

/**
 * This is an example of how to unregister a core block style.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-styles/
 * @see https://github.com/WordPress/gutenberg/pull/37580
 *
 * @since 1.0.0
 *
 * @return void
 */
function ellura_collections_unregister_block_style() {
	wp_enqueue_script(
		'ellura-collections-unregister',
		get_stylesheet_directory_uri() . '/assets/js/unregister.js',
		array( 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' ),
		ELLURA_COLLECTIONS_VERSION,
		true
	);
}
add_action( 'enqueue_block_editor_assets', 'ellura_collections_unregister_block_style' );
