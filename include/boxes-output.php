<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php

// ==================================================================
// Function to output the boxes in php pageas 
// ==================================================================

function yydev_portfolio_yydev_portfolio($box_id = "", $box_slug = "" ) {
    
    if( !empty($box_id) || !empty($box_slug) ) {
        yydev_portfolio_yydev_portfolioes('', '', $box_id, $box_slug);
    } // if( !empty($box_id) || !empty($box_slug) ) {
    
} // function yydev_portfolio_yydev_portfolio($id = "", $slug = "" ) {


// ==================================================================
// Function to create the box and output it on shortcode
// ==================================================================

function yydev_portfolio_yydev_portfolioes($attr = "", $content, $box_id = "", $box_slug = "", $box_limit = "") {

    include('settings.php');

    shortcode_atts( array('id' => '', 'name' => '', 'limit' => ''), $attr );
    
    // The function was loaded using shortcode
    if(!empty($attr['id'])) {
        $box_id = $attr['id'];
        $shortcode = "1";
    } // if(!empty($attr['id'])) {
    
    if(!empty($attr['name'])) {
        $box_slug = $attr['name'];
        $shortcode = "1";
    } // if(!empty($attr['id'])) {

    // limiting the amount of word it will display on the page
    $box_limit = '';
    if(!empty($attr['limit'])) {
        $box_limit = " LIMIT " . $attr['limit'] . " ";
    } // if(!empty($attr['limit'])) {

    if(!empty($box_id) || !empty($box_slug) ) {
        
        global $wpdb;
        $table_name = $yydev_portfolio_table_name;
        $box_table_name = $yydev_secondary_table_name;
        
        // Getting the box info
        if(!empty($box_slug)) {
            $box_slug_name = sanitize_text_field($box_slug);
            $main_box_info = $wpdb->get_row("SELECT * FROM " . $table_name . " WHERE slug = '{$box_slug_name}' ");
            if(!empty($main_box_info->id)) {
                $box_id = $main_box_info->id;
            } //if(!empty($main_box_info->id)) {
        } // if(!empty($box_id)) {

        if(!empty($box_id)) {
            $main_box_info = $wpdb->get_row("SELECT * FROM " . $table_name . " WHERE id = " . $box_id );
        } // if(!empty($box_id)) {
        
        // Choosing the order to display items
        $boxes_order_by = "DESC";
        if(!empty($main_box_info->order_by) && ($main_box_info->order_by == 1) ) {
            $boxes_order_by = "ASC";
        } // if(!empty($main_box_info->order_by)) {
        
        if( $wpdb->num_rows > 0 ) {
        
            // Getting the bxoes info
            // $wordpress_database_boxes = $wpdb->get_results("SELECT * FROM " . $box_table_name . " WHERE box_id = " . $box_id . " ORDER BY position ASC");
            if( ($main_box_info->hidden_load == 1) && empty($box_limit) ) {
                // if we load the boxes with hidden load we won't limit the work so all will show up on the page 
                $wordpress_database_boxes = $wpdb->get_results("SELECT * FROM " . $box_table_name . " WHERE box_id = " . $box_id . " ORDER BY position ASC");
            } else { // if( ($main_box_info->hidden_load == 1) && empty($box_limit) ) {
                // when we load the work th regular way we will limit the amount of work there is on the page
                $wordpress_database_boxes = $wpdb->get_results("SELECT * FROM " . $box_table_name . " WHERE box_id = " . $box_id . " ORDER BY position ASC " . $box_limit);
            } // } else { // if( ($main_box_info->hidden_load == 1) && empty($box_limit) ) {

        } // if( $wpdb->num_rows > 0 ) {

    } // if(!empty($box_id) || !empty($box_slug) ) {


    if( isset($wordpress_database_boxes) && ($wpdb->num_rows > 0) ) {
    
        // =======================================================
        // Loading the settings for the box we are using and loading
        // the output code for the page
        // =======================================================
        
        $box_type_name = $main_box_info->box_type;
        $box_setting_url = "boxes-templates/" . $box_type_name . "/settings.php"; // providing the settings to the box and the class

        if( ($main_box_info->hidden_load == 1) && empty($box_limit) ) {
            $box_output_url = "boxes-templates/" . $box_type_name . "/box-output-new-different-load.php"; // output the box to the page if it's the hidden load
        } else { // if( ($main_box_info->hidden_load == 1) && empty($box_limit) ) {
            $box_output_url = "boxes-templates/" . $box_type_name . "/box-output.php"; // output the box to the page if it's the regular load
        } // } else { // if( ($main_box_info->hidden_load == 1) && empty($box_limit) ) {

        $box_setting_full_url = dirname( __FILE__ ) . '/boxes-templates/' . $box_type_name . '/settings.php';
        
        if(file_exists($box_setting_full_url)) {
            
            // loading the seeting file in order to get the class for the box settings.php
            include($box_setting_url);
            
            // loading the file that output the box box-output.php
            include($box_output_url);
            
        } else { // if(file_exists($box_setting_url)) {

            $output_box = "<p style='color:#ff0000;font-size:16px;font-weight:bold;text-align:center;display:block;border:1px solid #ababab;padding:50px 20px;'>Error: unable to load the box plugin</p>";

        } // } else { // if(file_exists($box_setting_url)) {

    } else { // if( isset($wordpress_database_boxes) && ($wpdb->num_rows > 0) ) {
        $output_box = "<p style='color:#ff0000;font-size:16px;font-weight:bold;text-align:center;display:block;border:1px solid #ababab;padding:50px 20px;'>Error: unable to load the box plugin</p>";
    } // } else { // if( isset($wordpress_database_boxes) && ($wpdb->num_rows > 0) ) {

    
    // ================================================
    // Output the box to the page
    // ================================================
    
    if(isset($shortcode)) {
        // if it's used on short code it will return the string
        return $output_box;
    } else {
        // if it's used using php it will echo the string
        echo $output_box;
    }

} // function yydev_portfolio_yydev_portfolioes($box_id = "", $box_slug = "", $attr = "", $content=null) {

add_shortcode('wordpress-boxes', 'yydev_portfolio_yydev_portfolioes');


// ================================================
// Making sure the box wont have
// <p><span class="output-code"> box </span></p>
// around it, if the box is activate it will remove the
// <p><span class="output-code"> and </span></p>
// ================================================

function yydev_portfolio_remove_tags_from_shortcode( $content ) {
    
    if(strpos($content, '[wordpress-boxes')) {
        
        $content_length = strlen($content); // getting the length of the string
        $boxes_count = strpos($content, '[wordpress-boxes'); // checking where the box start
            
        $first_part_content = substr($content, 0, $boxes_count); // the place where the content start before the box
        $second_part_content = substr($content, $boxes_count, $content_length); // the place where the content start after the box 
        
        // Making sure <p><span class="output-code"> exists and exists only once
        if( substr_count($first_part_content, '<p><span class="output-code">') == 1 ) {
            $word_starting_count = strrpos($first_part_content, '<p><span class="output-code">');
            $first_part_content = substr($first_part_content, 0, $word_starting_count);
            
            $second_part_content = preg_replace("/\<\/span\>\<\/p\>/", "", $second_part_content, 1, $results);
        } // if( substr_count($testing_name, "<p><span class='output-code'>") == 1 ) {
        
        $content = $first_part_content . $second_part_content;
        return $content;
    
    } else { // if(strpos($content, 'wordpress-boxes')) {
        return $content;
    } // } else { // if(strpos($content, 'wordpress-boxes')) {
    
} // function yydev_portfolio_remove_tags_from_shortcode( $content ) {

add_filter("the_content", "yydev_portfolio_remove_tags_from_shortcode");
