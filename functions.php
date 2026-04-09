<?php

/**
 * Functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package ellura-collections
 * @since 1.0.0
 */

/**
 * The theme version.
 *
 * @since 1.0.0
 */
define('ELLURA_COLLECTIONS_VERSION', wp_get_theme()->get('Version'));

/**
 * Add theme support for block styles and editor style.
 *
 * @since 1.0.0
 *
 * @return void
 */
function ellura_collections_setup()
{
	add_editor_style('./assets/css/style-shared.min.css');

	/*
	 * Load additional block styles.
	 * See details on how to add more styles in the readme.txt.
	 */
	$styled_blocks = ['button', 'quote', 'navigation', 'search'];
	foreach ($styled_blocks as $block_name) {
		$args = array(
			'handle' => "ellura-collections-$block_name",
			'src'    => get_theme_file_uri("assets/css/blocks/$block_name.min.css"),
			'path'   => get_theme_file_path("assets/css/blocks/$block_name.min.css"),
		);
		// Replace the "core" prefix if you are styling blocks from plugins.
		wp_enqueue_block_style("core/$block_name", $args);
	}
}
add_action('after_setup_theme', 'ellura_collections_setup');

/**
 * Enqueue the CSS files.
 *
 * @since 1.0.0
 *
 * @return void
 */
// function ellura_collections_styles()
// {
// 	wp_enqueue_style(
// 		'ellura-collections-style',
// 		get_stylesheet_uri(),
// 		[],
// 		ELLURA_COLLECTIONS_VERSION
// 	);
// 	wp_enqueue_style(
// 		'ellura-collections-shared-styles',
// 		get_theme_file_uri('assets/css/style-shared.min.css'),
// 		[],
// 		ELLURA_COLLECTIONS_VERSION
// 	);
// }

// Styles:
function ellura_collections_styles()
{
	wp_enqueue_style(
		'ellura-collections-style',
		get_stylesheet_uri(),
		[],
		filemtime(get_stylesheet_directory() . '/style.css')
	);
	wp_enqueue_style(
		'ellura-collections-shared-styles',
		get_theme_file_uri('assets/css/style-shared.min.css'),
		[],
		filemtime(get_theme_file_path('assets/css/style-shared.min.css'))
	);
}
add_action('wp_enqueue_scripts', 'ellura_collections_styles');

// Filters.
require_once get_theme_file_path('inc/filters.php');

// Block variation example.
require_once get_theme_file_path('inc/register-block-variations.php');

// Block style examples.
require_once get_theme_file_path('inc/register-block-styles.php');

// Block pattern and block category examples.
require_once get_theme_file_path('inc/register-block-patterns.php');

// DISREGARD LINE - Test commit for deployment. 

// For future Text fields use and modify this code
// Validation for name field
add_filter('wpcf7_validate_text*', 'my_cf7_validate_name', 20, 2);
function my_cf7_validate_name($result, $tag)
{
	$name = $tag->name;

	if ($name === 'your-name') {
		$value = isset($_POST[$name]) ? wp_unslash($_POST[$name]) : '';
		$value = trim($value);

		// Allow letters, spaces, apostrophes, hyphens only
		if (!preg_match("/^[\p{L}\s'-]{2,60}$/u", $value)) {
			$result->invalidate($tag, 'Please enter a valid name.');
		}
	}

	return $result;
}

// For future email fields use and modify this code
// Validation for message field
add_filter('wpcf7_validate_textarea*', 'my_cf7_validate_message', 20, 2);
function my_cf7_validate_message($result, $tag)
{
	$name = $tag->name;

	if ($name === 'your-message') {
		$message = isset($_POST[$name]) ? wp_unslash($_POST[$name]) : '';
		$message = trim($message);

		if ($message !== wp_strip_all_tags($message)) {
			$result->invalidate($tag, 'HTML is not allowed in the message.');
		}

		if (preg_match('/(<script|javascript:|onerror=|onload=|select\s.+from|union\s+select|drop\s+table)/i', $message)) {
			$result->invalidate($tag, 'Your message contains disallowed content.');
		}
	}

	return $result;
}

add_filter('wpcf7_validate_text*', 'my_cf7_validate_subject', 20, 2);
function my_cf7_validate_subject($result, $tag)
{
	$name = $tag->name;

	if ($name === 'your-subject') {
		$subject = isset($_POST[$name]) ? wp_unslash($_POST[$name]) : '';
		$subject = trim($subject);

		// Allow letters, spaces, apostrophes, hyphens only
		if (!preg_match("/^[\p{L}\s'-]{2,60}$/u", $subject)) {
			$result->invalidate($tag, 'Please enter a valid subject tag.');
		}
	}

	return $result;
}

// For future Text-area fields use and modify this code
// Validation for Text-area field
add_filter('wpcf7_validate_textarea*', 'secure_cf7_message_field', 20, 2);

function secure_cf7_message_field($result, $tag)
{

	$tag_name = $tag->name;

	if ($tag_name === 'your-message') {

		$messagev = isset($_POST[$tag_name]) ? wp_unslash($_POST[$tag_name]) : '';

		$messagev = sanitize_textarea_field($messagev);

		if ($messagev !== wp_strip_all_tags($messagev)) {
			$result->invalidate($tag, "HTML is not allowed in the message.");
			return $result;
		}

		$patterns = '/(<script|javascript:|onerror=|onload=|union\s+select|drop\s+table|insert\s+into|delete\s+from)/i';

		if (preg_match($patterns, $messagev)) {
			$result->invalidate($tag, "Your message contains disallowed content.");
		}
	}

	return $result;
}

// Validation for Email field
add_filter('wpcf7_validate_email', 'cf7_protect_email_field', 20, 2);
add_filter('wpcf7_validate_email*', 'cf7_protect_email_field', 20, 2);

function cf7_protect_email_field($result, $tag)
{
	if ($tag->name !== 'your-email') {
		return $result;
	}

	$email = isset($_POST['your-email']) ? wp_unslash($_POST['your-email']) : '';
	$email = trim($email);

	if (preg_match('/[\r\n]/', $email)) {
		$result->invalidate($tag, 'Invalid email address.');
		return $result;
	}

	if (preg_match('/(bcc:|cc:|content-type:|mime-version:|multipart\/mixed)/i', $email)) {
		$result->invalidate($tag, 'Invalid email address.');
		return $result;
	}

	$sanitized_email = sanitize_email($email);

	if (empty($sanitized_email) || !is_email($sanitized_email)) {
		$result->invalidate($tag, 'Please enter a valid email address.');
		return $result;
	}

	$_POST['your-email'] = $sanitized_email;

	return $result;
}

add_action('wp_head', function () {
	if (is_page() || is_single()) {
		echo '<meta name="description" content="' . get_the_excerpt() . '">';
	}
});

add_action('wp_head', function () {
	echo '<meta property="og:title" content="' . get_the_title() . '">';
});


add_action('wp_head', function () {
?>
	<script type="application/ld+json">
		{
			"@context": "https://schema.org",
			"@type": "WebPage",
			"name": "<?php echo get_the_title(); ?>"
		}
	</script>
<?php
});

/**
 * Shortcode: [products_by_tags_in_category category="womans-clothes"]
 *
 * Shows products from one WooCommerce product category,
 * grouped by product tag.
 */
function dr_products_by_tags_in_category_shortcode($atts)
{
	if (! class_exists('WooCommerce')) {
		return '<p>WooCommerce is not active.</p>';
	}

	$atts = shortcode_atts(
		array(
			'category'           => '',     // product category slug
			'orderby_tags'       => 'name', // tag sorting: name, slug, id, count
			'order_tags'         => 'ASC',  // ASC or DESC
			'orderby_products'   => 'title', // product sorting inside each tag
			'order_products'     => 'ASC',  // ASC or DESC
			'hide_empty_tags'    => 'true', // true or false
		),
		$atts,
		'products_by_tags_in_category'
	);

	$category_slug = sanitize_title($atts['category']);

	if (empty($category_slug)) {
		return '<p>Please provide a product category slug.</p>';
	}

	$hide_empty_tags = filter_var($atts['hide_empty_tags'], FILTER_VALIDATE_BOOLEAN);

	// Step 1: Get product IDs in this category
	$product_ids = get_posts(array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'fields'         => 'ids',
		'posts_per_page' => -1,
		'tax_query'      => array(
			array(
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => $category_slug,
			),
		),
	));

	if (empty($product_ids)) {
		return '<p>No products found in this category.</p>';
	}

	// Step 2: Get product tags actually used by those products
	$tags = wp_get_object_terms(
		$product_ids,
		'product_tag',
		array(
			'orderby'    => $atts['orderby_tags'],
			'order'      => $atts['order_tags'],
			'hide_empty' => $hide_empty_tags,
		)
	);

	if (is_wp_error($tags) || empty($tags)) {
		return '<p>No product tags found for this category.</p>';
	}

	// Remove duplicates just in case
	$unique_tags = array();
	foreach ($tags as $tag) {
		$unique_tags[$tag->term_id] = $tag;
	}
	$tags = array_values($unique_tags);

	ob_start();

	echo '<div class="products-by-tags-in-category">';

	foreach ($tags as $tag) {
		$product_query = new WP_Query(array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => $atts['orderby_products'],
			'order'          => $atts['order_products'],
			'tax_query'      => array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => $category_slug,
				),
				array(
					'taxonomy' => 'product_tag',
					'field'    => 'term_id',
					'terms'    => $tag->term_id,
				),
			),
		));

		if ($product_query->have_posts()) {
			echo '<section class="product-tag-group">';
			echo '<h2 class="product-tag-title">' . esc_html($tag->name) . '</h2>';
			echo '<ul class="products columns-4">';

			while ($product_query->have_posts()) {
				$product_query->the_post();
				wc_get_template_part('content', 'product');
			}

			echo '</ul>';
			echo '</section>';
		}

		wp_reset_postdata();
	}

	echo '</div>';

	return ob_get_clean();
}
add_shortcode('products_by_tags_in_category', 'dr_products_by_tags_in_category_shortcode');

// ---------------------------------------------------------------
// SIERRA BRAVO
add_filter('acf/update_value/name=top_sales_banner_text', function ($input) {
	return sanitize_text_field($input);
}, 10, 1);

add_action('wp_body_open', function () {

	// NOTE #DEV1, for ID 75.
	$settings_page_id = 64;

	$enabled = get_field('turn_on_top_banner_sales_section', $settings_page_id);
	$text    = get_field('top_sales_banner_text', $settings_page_id);

	if (!$enabled || !$text) return;

	echo '<div class="sales--banner">
        <div class="sales--banner-track top-level-sales-banner">
            <p>' . esc_html($text) . '</p>
            <p>' . esc_html($text) . '</p>
            <p>' . esc_html($text) . '</p>
        </div>
      </div>';
});

function display_chartfield_content()
{
	$related_post = get_field('chartfield');

	if (!$related_post) {
		return '';
	}

	// If the field returns a Post Object
	if (is_object($related_post) && isset($related_post->ID)) {
		$post_id = $related_post->ID;
	} else {
		// If the field returns a Post ID
		$post_id = $related_post;
	}

	$post = get_post($post_id);

	if (!$post) {
		return '';
	}

	$output  = '<article class="chartfield-content">';
	$output .= get_the_post_thumbnail($post_id, 'large');
	$output .= apply_filters('the_content', $post->post_content);
	$output .= '</article>';

	return $output;
}
add_shortcode('chartfield_content', 'display_chartfield_content');

function wc_attributes_exclude_size_shortcode($atts)
{
	global $product;

	if (!$product) return '';

	$attributes = $product->get_attributes();
	$exclude = ['pa_size']; // excluded attribute slugs

	$items = [];

	// Add product attributes except excluded ones
	if (!empty($attributes)) {
		foreach ($attributes as $attribute) {
			$attr_name = $attribute->get_name();

			if (in_array($attr_name, $exclude, true)) {
				continue;
			}

			if ($attribute->get_visible()) {
				$label = wc_attribute_label($attr_name);

				if ($attribute->is_taxonomy()) {
					$values = wc_get_product_terms(
						$product->get_id(),
						$attr_name,
						['fields' => 'names']
					);
				} else {
					$values = $attribute->get_options();
				}

				if (!empty($values)) {
					$items[] = '<div class="product-attribute-item"><strong>' . esc_html($label) . ':</strong> ' . esc_html(implode(', ', $values)) . '</div>';
				}
			}
		}
	}

	// Add dimensions
	$dimensions = wc_format_dimensions($product->get_dimensions(false));
	if (!empty($dimensions)) {
		$items[] = '<div class="product-attribute-item"><strong>Dimensions:</strong> ' . esc_html($dimensions) . '</div>';
	}

	if (empty($items)) {
		return '';
	}

	return '<div class="product-attributes">' . implode('', $items) . '</div>';
}
add_shortcode('attributes_no_size', 'wc_attributes_exclude_size_shortcode');

// ----------------------------------------------------
// JavaScript:

function elluracollection_script()
{
	wp_enqueue_script(
		'main-javascript',
		get_template_directory_uri() . '/assets/js/main.js',
		array(),
		filemtime(get_template_directory() . '/assets/js/main.js'),
		true
	);
}
add_action('wp_enqueue_scripts', 'elluracollection_script');

function inside_site_title_shortcode()
{
	return '<h2 class="wp-block-heading has-large-font-size" style="text-transform: uppercase; text-align:center;">INSIDE ' . get_bloginfo('name') . '</h2>';
}
add_shortcode('inside_site_title', 'inside_site_title_shortcode');
