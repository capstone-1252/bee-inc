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
function ellura_collections_styles()
{
	wp_enqueue_style(
		'ellura-collections-style',
		get_stylesheet_uri(),
		[],
		ELLURA_COLLECTIONS_VERSION
	);
	wp_enqueue_style(
		'ellura-collections-shared-styles',
		get_theme_file_uri('assets/css/style-shared.min.css'),
		[],
		ELLURA_COLLECTIONS_VERSION
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

// ------------------------------------------------------------
// ACF Control for Top Level Sales Banner

// $banner_enabled = get_field( 'turn_on_top_banner_sales_section', $post_id );
// $banner_text    = get_field( 'top_sales_banner_text', $post_id );

// add_shortcode('top_sales_banner', 'top_sales_banner_shortcode');

// function my_theme_register_blocks() {
//     register_block_type( get_template_directory() . '/block.json' );
// }

// add_action( 'init', 'my_theme_register_blocks' );

// function sales_banner_shortcode() {
//     return '<div style="background: red; color: white; padding: 20px; text-align: center;"> THIS SHORTCODE WORKS </div>';
// }

// add_shortcode( 'sales_banner', 'sales_banner_shortcode' );

// AI Reference.
// The Advanced Custom Field option for the sales banner at the top, in the <header>:
// !!!!!!!!!!!!!!!!!!!!!!!!!!!! THIS CODE IS IN PROGRESS & STILL NEEDS SECRUITY CHECKS!
function sales_banner_shortcode() {
    $banner_enabled = get_field('turn_on_top_banner_sales_section' );
    $banner_text = get_field('top_sales_banner_text' );
    
    if (!$banner_enabled || !$banner_text ) {
        return "";
    }
    
    return '
	<p>' . wp_kses_post(esc_html(htmlspecialchars($banner_text)) ) . '</p>
	';
}

add_shortcode('sales_banner', 'sales_banner_shortcode');
// ------------------------------------------------------------

add_action('wp_head', function() {
    if (is_page() || is_single()) {
        echo '<meta name="description" content="' . get_the_excerpt() . '">';
    }
});

add_action('wp_head', function() {
    echo '<meta property="og:title" content="' . get_the_title() . '">';
});


add_action('wp_head', function() {
?>
<script type="application/ld+json">
{
 "@context":"https://schema.org",
 "@type":"WebPage",
 "name":"<?php echo get_the_title(); ?>"
}
</script>
<?php
});
