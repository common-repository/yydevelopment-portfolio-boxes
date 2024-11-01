<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php

// ==================================================================
// Creating a code that will output next and pev meta tags for
// pages with portfolio that has more than one page
// <link rel="prev" href="http://www.url.co.il" />
// <link rel="next" href="http://www.url.co.il/page/3/" />
// ==================================================================

function yydev_portfolio_add_next_prev_meta_tags() {
    
    include('settings.php');

    global $wpdb;
    $table_name = $yydev_portfolio_table_name;
    $box_table_name = $yydev_secondary_table_name;
    $page_id_number = get_the_ID();


    // making sure that we don't make the text on blog pages and other related pages
    if( is_category() || is_home() || is_search() || is_tag() ) { 
        $dont_check_for_pages_meta = 1;
    } // if( is_category() || is_home() || is_search() || is_tag() ) { 


    // if there are no error we will check if to output the meta tags to the header
    if(!isset($dont_check_for_pages_meta)) {
        
        $get_page_box_info = $wpdb->get_row("SELECT * FROM " . $table_name . " WHERE parent_page_id = '{$page_id_number}' ");
        
        // making sure that the page id exists on the database and we also
        // check to see that this portfolio has set 'items_per_page' on the databse
        if( !empty($get_page_box_info->items_per_page) ) {
                
            // getting all the required info in order to understand
            // how many pages we are going to have

            $wordpress_database_boxes = $wpdb->get_results("SELECT * FROM " . $box_table_name . " WHERE box_id = " . $get_page_box_info->id . " ORDER BY position ASC ");

            $items_number = $wpdb->num_rows;
            $itmes_per_page = $get_page_box_info->items_per_page;
            $page_path = get_the_permalink();
            $current_page_number = get_query_var('paged');
            $pages_amount = ceil(($items_number / $itmes_per_page));

            // output the code only if there is more than one page
            // we are checking if there are more images than what we allow on the page
            if( $items_number > $itmes_per_page ) {

                $next_prev_header_code = "";

                // ----------------------------------------------------------
                // incase it's the first page we will only show the next page (page 2)
                // ----------------------------------------------------------
                if($current_page_number == 0) {
                    $next_prev_header_path = $page_path . "page/2/";
                    $next_prev_header_code .= "<link rel='next' href='" . $next_prev_header_path . "' />";
                } // if($current_page_number == 0) {

                // ----------------------------------------------------------
                // if it's not the first page and we have more page then 
                // the page we are viewing at the moment we will output the next page
                // ----------------------------------------------------------
                if( ($current_page_number > 0) && ($pages_amount > $current_page_number) ) {
                    $next_number = $current_page_number + 1;
                    $next_prev_header_path = $page_path . "page/" . $next_number . "/";
                    $next_prev_header_code .= "<link rel='next' href='" . $next_prev_header_path . "' />";
                } // if($current_page_number == 0) {

                // ----------------------------------------------------------
                // with this code we will output the previous button if it's 
                // the second page we will return to the main page url
                // and if it's higher page than the second we will go to the previous page
                // ----------------------------------------------------------
                if($current_page_number > 1) {
                    
                    if($current_page_number == 2) {
                        $previous_page_number = "";
                    } else { // if($current_page_number == 2) {
                        $previous_number = $current_page_number - 1;
                        $previous_page_number = "page/" . $previous_number . "/";
                    } // if($current_page_number == 2) {

                    $next_prev_header_path = $page_path . $previous_page_number;
                    $next_prev_header_code .= "<link rel='prev' href='" . $next_prev_header_path . "' />";

                } // if($current_page_number == 0) {

                echo $next_prev_header_code . "\n";

            } // if( $items_number > $itesm_per_page ) {

        } // if( !empty($get_page_box_info-> items_per_page) ) {

    } // if(!isset($dont_check_for_pages_meta)) {


} // function yydev_portfolio_add_next_prev_meta_tags() {


add_action('wp_head', 'yydev_portfolio_add_next_prev_meta_tags');


// ====================================================================
// we use the filter add_filter('wpseo_canonical', 'yydev_portfolio_add_next_prev_meta_tags');
// to change the canonical tag if it's required on the portfolio pages
// ====================================================================

function yydev_portfolio_change_pages_canonical_meta($canonical) {

    include('settings.php');

    global $wpdb;
    $table_name = $yydev_portfolio_table_name;
    $box_table_name = $yydev_secondary_table_name;
    $page_id_number = get_the_ID();

    // making sure that we don't make the text on blog pages and other related pages
    if( is_category() || is_home() || is_search() || is_tag() ) { 
        $dont_check_for_pages_meta = 1;
    } // if( is_category() || is_home() || is_search() || is_tag() ) { 

    // if there are no error we will check if to output the meta tags to the header
    if(!isset($dont_check_for_pages_meta)) {
        
        $get_page_box_info = $wpdb->get_row("SELECT * FROM " . $table_name . " WHERE parent_page_id = '{$page_id_number}' ");
        
        // making sure that the page id exists on the database and we also
        // check to see that this portfolio has set 'items_per_page' on the databse
        if( !empty($get_page_box_info->items_per_page) ) {
                
            // getting all the required info in order to understand
            // how many pages we are going to have

            $wordpress_database_boxes = $wpdb->get_results("SELECT * FROM " . $box_table_name . " WHERE box_id = " . $get_page_box_info->id . " ORDER BY position ASC " . $box_limit);

            $items_number = $wpdb->num_rows;
            $itmes_per_page = $get_page_box_info->items_per_page;
            $page_path = get_the_permalink();
            $current_page_number = get_query_var('paged');
            $pages_amount = ceil(($items_number / $itmes_per_page));

            if( $items_number > $itmes_per_page ) {

                if($current_page_number > 0) {
                    $canonical = $page_path . "page/" . $current_page_number . "/";
                } // if($current_page_number > 0) {

            } // if( $items_number > $itesm_per_page ) {

        } // if( !empty($get_page_box_info->items_per_page) ) {

    } // if(!isset($dont_check_for_pages_meta)) {

    // return the canonical url, if we haven't set a new one it
    //  will return with the same canonical url the page had
    return $canonical;

} // function yydev_portfolio_change_pages_canonical_meta($canonical) {

if ( function_exists('yoast_breadcrumb') ) {
    // add canonical meata tag
    add_filter('wpseo_canonical', 'yydev_portfolio_change_pages_canonical_meta');
} // if ( function_exists('yoast_breadcrumb') ) {