<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php
/*
Plugin Name: YYDevelopment - Portfolio Gallery
Plugin URI:  https://www.yydevelopment.com/yydevelopment-wordpress-plugins/
Description: Simple plugin that will allow to to create boxes portfolios in wordpress
Version:     1.4.0
Author:      YYDevelopment
Author URI:  https://www.yydevelopment.com/
Text Domain: yydevelopment-portfolio-boxes
*/

// ================================================
// Adding lanagues support to the plugin
// ================================================

function yydev_portfolio_boxes() {
  load_plugin_textdomain( 'yydevelopment-portfolio-boxes', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
} // function yydev_portfolio_boxes() {
	
add_action( 'plugins_loaded', 'yydev_portfolio_boxes' );

// ================================================
// Including important files
// ================================================

include('include/settings.php');
require_once('include/wordpress-functions.php');
include_once('include/add-next-prev-meta-tags.php');

$yydev_portfolioes_plugin_version = '1.2.0'; // plugin version
$yydev_portfolioes_data_slug_name = 'yydev_portfolioes_version'; // the name we save on the wp_options database

// ================================================
// Loading the css file
// ================================================

function yydev_portfolio_register_css( $content ) {
    wp_register_style('yydev_portfolio', path_join(WP_PLUGIN_URL, basename( dirname( __FILE__ ) )."/style/yydev_portfolio.css") );
    wp_enqueue_style( 'yydev_portfolio' );
}

add_action('wp_enqueue_scripts', 'yydev_portfolio_register_css');

// ================================================
// update the database on plugin update
// ================================================

// loading the plugin version from the database
$db_plugin_version = get_option($yydev_portfolioes_data_slug_name);

// checking if the plugin version exists on the dabase
// and checking if the database version equal to the plugin version $yydev_portfolioes_plugin_version
if( empty($db_plugin_version) || ($yydev_portfolioes_plugin_version != $db_plugin_version) ) {

    // update the plugin database if it's required
    $yydev_portfolioes_database_update = 1;
    require_once('include/install.php');

    // update the plugin version in the database
    update_option($yydev_portfolioes_data_slug_name, $yydev_portfolioes_plugin_version);

} // if( empty($db_plugin_version) || ($yydev_portfolioes_plugin_version != $db_plugin_version) ) {

// add_action('plugins_loaded', 'my_awesome_plugin_check_version');

// ================================================
// Creating Database when the plugin is activated
// ================================================

function yydev_portfolio_create_database() {
    
    require_once('include/install.php');
        
} // function yydev_portfolio_create_database() {

register_activation_hook(__FILE__, 'yydev_portfolio_create_database');


// ================================================
// Adding menu tag inside wordpress admin panel
// ================================================

function yydev_portfolio_page() {
    
    include('include/settings.php');

    global $wpdb;
    $table_name = $yydev_portfolio_table_name;
    $box_table_name = $yydev_secondary_table_name;
    
    include('include/style.php');
    include('include/script.php');
    
    // Including the main script page, The box Page
    
    if( isset($_GET['view']) && isset($_GET['id']) && ($_GET['view'] = 'box') ) {
        include('include/secondary-page.php');
    } else {
        include('include/main-page.php');
    }
}// function yydev_portfolio_page() {

function yydev_portfolio_plugin_menu() {
    $wordpress_icon_path = path_join(WP_PLUGIN_URL, basename( dirname( __FILE__ ) )."/images/favicon.png");
    add_menu_page('Portfolio Gallery','Portfolio Gallery', 'manage_options', 'yydev_portfolio', 'yydev_portfolio_page',  $wordpress_icon_path, 500);
} // function yydev_portfolio_plugin_menu() {

add_action('admin_menu', 'yydev_portfolio_plugin_menu');

// ================================================
// Add settings page to the plugin menu info
// ================================================

function yydev_portfolio__add_settings_link( $actions, $plugin_file ) {
	static $plugin;


    if (!isset($plugin)) { $plugin = plugin_basename(__FILE__); }

	if ($plugin == $plugin_file) {

            $admin_page_url = esc_url( menu_page_url( 'yydev_portfolio', false ) );
			$settings = array('settings' => '<a href="' . $admin_page_url . '">Settings</a>');
            $donate = array('donate' => '<a target="_blank" href="https://www.yydevelopment.com/coffee-break/?plugin=yydevelopment-portfolio-boxes">Donate</a>');
            $actions = array_merge($settings, $donate, $actions);

    } // if ($plugin == $plugin_file) {

    return $actions;

} //function yydev_portfolio__add_settings_link( $actions, $plugin_file ) {

add_filter( 'plugin_action_links', 'yydev_portfolio__add_settings_link', 10, 5 );

// ================================================
// Output the shortcode the the template tags
// ================================================

include('include/boxes-output.php');

// ================================================
// including admin notices flie
// ================================================

if( is_admin() ) {
	include_once('notices.php');
} // if( is_admin() ) {