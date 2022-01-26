<?php
/**
 * Twenty Twenty-Two functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Two
 * @since Twenty Twenty-Two 1.0
 */


if ( ! function_exists( 'twentytwentytwo_support' ) ) :

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * @since Twenty Twenty-Two 1.0
	 *
	 * @return void
	 */
	function twentytwentytwo_support() {

		// Add support for block styles.
		add_theme_support( 'wp-block-styles' );

		// Enqueue editor styles.
		add_editor_style( 'style.css' );

	}

endif;

add_action( 'after_setup_theme', 'twentytwentytwo_support' );

if ( ! function_exists( 'twentytwentytwo_styles' ) ) :

	/**
	 * Enqueue styles.
	 *
	 * @since Twenty Twenty-Two 1.0
	 *
	 * @return void
	 */
	function twentytwentytwo_styles() {
		// Register theme stylesheet.
		$theme_version = wp_get_theme()->get( 'Version' );

		$version_string = is_string( $theme_version ) ? $theme_version : false;
		wp_register_style(
			'twentytwentytwo-style',
			get_template_directory_uri() . '/style.css',
			array(),
			$version_string
		);

		// Add styles inline.
		wp_add_inline_style( 'twentytwentytwo-style', twentytwentytwo_get_font_face_styles() );

		// Enqueue theme stylesheet.
		wp_enqueue_style( 'twentytwentytwo-style' );

	}

endif;

add_action( 'wp_enqueue_scripts', 'twentytwentytwo_styles' );

if ( ! function_exists( 'twentytwentytwo_editor_styles' ) ) :

	/**
	 * Enqueue editor styles.
	 *
	 * @since Twenty Twenty-Two 1.0
	 *
	 * @return void
	 */
	function twentytwentytwo_editor_styles() {

		// Add styles inline.
		wp_add_inline_style( 'wp-block-library', twentytwentytwo_get_font_face_styles() );

	}

endif;

add_action( 'admin_init', 'twentytwentytwo_editor_styles' );


if ( ! function_exists( 'twentytwentytwo_get_font_face_styles' ) ) :

	/**
	 * Get font face styles.
	 * Called by functions twentytwentytwo_styles() and twentytwentytwo_editor_styles() above.
	 *
	 * @since Twenty Twenty-Two 1.0
	 *
	 * @return string
	 */
	function twentytwentytwo_get_font_face_styles() {

		return "
		@font-face{
			font-family: 'Source Serif Pro';
			font-weight: 200 900;
			font-style: normal;
			font-stretch: normal;
			font-display: swap;
			src: url('" . get_theme_file_uri( 'assets/fonts/SourceSerif4Variable-Roman.ttf.woff2' ) . "') format('woff2');
		}

		@font-face{
			font-family: 'Source Serif Pro';
			font-weight: 200 900;
			font-style: italic;
			font-stretch: normal;
			font-display: swap;
			src: url('" . get_theme_file_uri( 'assets/fonts/SourceSerif4Variable-Italic.ttf.woff2' ) . "') format('woff2');
		}
		";

	}

endif;

if ( ! function_exists( 'twentytwentytwo_preload_webfonts' ) ) :

	/**
	 * Preloads the main web font to improve performance.
	 *
	 * Only the main web font (font-style: normal) is preloaded here since that font is always relevant (it is used
	 * on every heading, for example). The other font is only needed if there is any applicable content in italic style,
	 * and therefore preloading it would in most cases regress performance when that font would otherwise not be loaded
	 * at all.
	 *
	 * @since Twenty Twenty-Two 1.0
	 *
	 * @return void
	 */
	function twentytwentytwo_preload_webfonts() {
		?>
		<link rel="preload" href="<?php echo esc_url( get_theme_file_uri( 'assets/fonts/SourceSerif4Variable-Roman.ttf.woff2' ) ); ?>" as="font" type="font/woff2" crossorigin>
		<?php
	}

endif;

add_action( 'wp_head', 'twentytwentytwo_preload_webfonts' );

// Add block patterns
require get_template_directory() . '/inc/block-patterns.php';

add_action("init", "register_brewers_cpt");
function register_brewers_cpt() {
	register_post_type("brewery", [
		'label' => "Breweries",
		'public' => true,
		'capability_type' => "post"
	]);
}

if( !wp_next_scheduled('update_brewery_list') ) {
	wp_schedule_event(time(), 'weekly', 'get_breweries_from_api');
}

add_action('wp_ajax_nopriv_get_breweries_from_api', 'get_breweries_from_api');
add_action('wp_ajax_get_breweries_from_api', 'get_breweries_from_api');

function get_breweries_from_api() {

	$file = get_stylesheet_directory() . "/report.txt";

	$current_page = ( !empty($_POST["current_page"]) ) ? $_POST["current_page"] : 1 ;
	$breweries = [];

	$results = wp_remote_retrieve_body(wp_remote_get('https://api.openbrewerydb.org/breweries/?page=' . $current_page . '&per_page=50'));

	file_put_contents($file, "Current page:" . $current_page. "\n\n" . FILE_APPEND);

	$results = json_decode($results);

	if( !is_array($results) || empty($results) ) {
		return false;
	}

	$breweries[] = $results;

	foreach ($breweries[0] as $brewery) {
		$brewery_slug = sanitize_title($brewery->name . "-" . $brewery->id);

		$existing_brewery = get_page_by_path($brewery_slug, 'OBJECT', 'brewery');

		if($existing_brewery === null) {
			$inserted_brewery = wp_insert_post([
				'post_name' => $brewery_slug,
				'post_title' => $brewery_slug,
				'post_type' => "brewery",
				'post_status' => "publish"
			]);

			if( is_wp_error($inserted_brewery) ) {
				continue;
			}

			$fillable = [
				'field_61f1227beeaa1' => 'name',
				'field_61f1228aeeaa2' => 'brewery_type',
				'field_61f12293eeaa3' => 'street',
				'field_61f122ebeeaa4' => 'city',
				'field_61f122f1eeaa5' => 'state',
				'field_61f12301eeaa6' => 'postal_code',
				'field_61f12306eeaa7' => 'country',
				'field_61f12314eeaa8' => 'longitude',
				'field_61f12319eeaa9' => 'latitude',
				'field_61f1232deeaaa' => 'phone',
				'field_61f12348eeaab' => 'website',
				'field_61f1234ceeaac' => 'updated_at'
			];

			foreach ($fillable as $key => $name) {
				update_field($key, $brewery->$name, $inserted_brewery);
			}
		} else {
			$existing_brewery_id = $existing_brewery->ID;
			$existing_brewery_timestamp = get_field("updated_at", $existing_brewery_id);

			if( $brewery->updated_at >= $existing_brewery_timestamp ) {
				$fillable = [
					'field_61f1227beeaa1' => 'name',
					'field_61f1228aeeaa2' => 'brewery_type',
					'field_61f12293eeaa3' => 'street',
					'field_61f122ebeeaa4' => 'city',
					'field_61f122f1eeaa5' => 'state',
					'field_61f12301eeaa6' => 'postal_code',
					'field_61f12306eeaa7' => 'country',
					'field_61f12314eeaa8' => 'longitude',
					'field_61f12319eeaa9' => 'latitude',
					'field_61f1232deeaaa' => 'phone',
					'field_61f12348eeaab' => 'website',
					'field_61f1234ceeaac' => 'updated_at'
				];

				foreach ($fillable as $key => $name) {
					update_field($key, $brewery->$name, $existing_brewery_id);
				}
			}
		}
	}

	$current_page = $current_page + 1;

	wp_remote_post( admin_url('admin-ajax.php?action=get_breweries_from_api'), [
		'blocking' => false,
		'sslverify' => false,
		'body' => [
			'current_page' => $current_page
		]
	] );

}