<?php
if ( ! defined( 'WPINC' ) ) { die('Direct access prohibited!'); }
/**
 * Add submenu item to the default WordPress "Settings" menu.
 */
function iewp_metadata()
{
	add_submenu_page(
		'options-general.php', // parent slug to attach to
		'Metadata', // page title
		'Metadata', // menu title
		'manage_options', // capability
		'iewp-metadata', // slug
		'iewp_metadata_callback' // callback function
		);

	// Activate custom settings
	add_action( 'admin_init', 'iewp_metadata_register' );
}
add_action( 'admin_menu', 'iewp_metadata' );

/**
 * Register custom settings
 */
function iewp_metadata_register()
{
	/**
	 * Register the settings fields
	 */
	register_setting(
		'iewp_metadata_group', // option group
		'iewp_twitter_cards_user', // option name
		'iewp_twitter_cards_user_sanitize' // sanitize function
		);

	register_setting(
		'iewp_metadata_group', // option group
		'iewp_enable_twitter_cards' // option name
		);

	register_setting(
		'iewp_metadata_group', // option group
		'iewp_enable_open_graph' // option name
		);

	register_setting(
		'iewp_metadata_group', // option group
		'iewp_default_open_graph_img' // option name
		);

	/**
	 * Create the settings section for this group of settings
	 */
	add_settings_section(
		'iewp-metadata', // id
		'Metadata Theme Options', // title
		'iewp_metadata_section', // callback
		'iewp_metadata' // page
		);

	/**
	 * Add the settings fields
	 */
	add_settings_field(
		'iewp-twitter-cards-user', // id
		'Twitter Account', // title/label
		'iewp_twitter_cards_user', // callback
		'iewp_metadata', // page
		'iewp-metadata' // settings section
		);

	add_settings_field(
		'iewp-enable-twitter-cards', // id
		'Enable Twitter Cards', // title/label
		'iewp_enable_twitter_cards', // callback
		'iewp_metadata', // page
		'iewp-metadata' // settings section
		);

	add_settings_field(
		'iewp-enable-open-graph', // id
		'Enable Open Graph', // title/label
		'iewp_enable_open_graph', // callback
		'iewp_metadata', // page
		'iewp-metadata' // settings section
		);

	add_settings_field(
		'iewp-default-open-graph-img', // id
		'Default Open Graph Image', // title/label
		'iewp_default_open_graph_img', // callback
		'iewp_metadata', // page
		'iewp-metadata' // settings section
		);

}

/**
 * The callbacks
 */
function iewp_metadata_section()
{
	return;
}

function iewp_twitter_cards_user()
{
    $setting = esc_attr( get_option( 'iewp_twitter_cards_user' ) );
    echo '<input type="text" class="regular-text" name="iewp_twitter_cards_user" value="'.$setting.'" placeholder="handle">';
	echo '<p class="description">Your Twitter username without the @ symbol.</p>';
}

function iewp_twitter_cards_user_sanitize( $input )
{
	$output = ltrim( $input, '@' );
	return $output;
}

function iewp_enable_twitter_cards()
{
    $id = 'iewp_enable_twitter_cards';
	$options = array('true','false');
	$default = 'false';
	$description = 'Insert Twitter Cards metadata into <code>&lt;HEAD&gt;</code> element.';
	echo iewp_metadata_options_select( $id, $options, $default, $description);
}

function iewp_enable_open_graph()
{
    $id = 'iewp_enable_open_graph';
	$options = array('true','false');
	$default = 'false';
	$description = 'Insert Open Graph metadata into <code>&lt;HEAD&gt;</code> element.';
	echo iewp_metadata_options_select( $id, $options, $default, $description);
}

function iewp_default_open_graph_img()
{
	$setting = esc_attr( get_option( 'iewp_default_open_graph_img' ) );
	echo '<button id="upload-img" class="button button-secondary upload-img">Choose Image</button>';
	echo '<input id="img-url" type="text" class="regular-text" name="iewp_default_open_graph_img" value="'.$setting.'" placeholder="Image URL ...">';
	echo '<p class="description">Your default Open Graph image. Minimum <a target="_blank" href="https://developers.facebook.com/docs/sharing/best-practices">recommended</a> size is 1200px x 630px.</p>';
	echo '<div id="img-preview" class="img-preview" data-default="' . plugin_dir_url( __FILE__ ) . 'assets/open-graph-default.png"></div>';
}

/**
 * Enqueue additional JavaScript and CSS
 */
function iewp_metadata_enqueue_scripts( $hook )
{
	if( 'settings_page_iewp-metadata' != $hook )
	{
		return;
	}
	wp_register_style( 'iewp_metadata_css', plugin_dir_url( __FILE__ ) . 'assets/iewp_metadata.css', array(), '0.0.1', 'all' );
	wp_enqueue_style( 'iewp_metadata_css' );

	wp_register_script( 'iewp_metadata_js', plugin_dir_url( __FILE__ ) . 'assets/iewp_metadata.js', array('jquery'), '0.0.1', true );
	wp_enqueue_script( 'iewp_metadata_js' );

	wp_enqueue_media();
}
add_action( 'admin_enqueue_scripts', 'iewp_metadata_enqueue_scripts' );

/**
 * Output the page
 */
function iewp_metadata_callback()
{
	?>
	<div class="wrap">

		<h1>Metadata Options</h1>

		<p>Enable/disable support for Open Graph and Twitter metadata tags.</p>

		<hr>

		<form method="POST" action="options.php">

			<?php settings_fields( 'iewp_metadata_group' ); ?>
			<?php do_settings_sections( 'iewp_metadata' ); ?>
			<?php submit_button(); ?>

		</form>

	</div>
	<?php
}

/**
 * Produces select for options
 */
function iewp_metadata_options_select( $id, $options, $default, $description = '' )
{
	$setting = get_option( $id, $default );
	$html = '<select name="'.$id.'">';
        foreach ( $options as $option )
        {
        	$selected = '';
        	if ( $option == $setting )
        	{
        		$selected = ' selected="selected"';
        	}
        	$html .= '<option value="'.$option.'"'.$selected.'>'.$option.'</option>';
        }
	$html .= '</select>';
	if($description != '')
	{
    	$html .= '<p class="description">' . $description . '</p>';
    }
    return $html;
}
