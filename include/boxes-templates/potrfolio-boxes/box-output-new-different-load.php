<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php
            
    // ==================================================================
    // Creating all the box info that will be display on the page
    // ==================================================================

    $output_box = "";
    $output_box .= "\n\n";
    
    $container_width_style = "";
    if( !empty($main_box_info->container_width) ) {
        $container_width_style =  " style='max-width:" . $main_box_info->container_width . ";'";
    } // if( !empty($db_slider_info->width) ) {

    // including the class that set on settings.php file    
    $output_box_class = "";
    if(isset($main_box_class)) {
        $output_box_class = " " . $main_box_class;
    } // if(isset($main_box_class)) {
    
    $output_box .= "<div class='yydev_portfolio_warp'>";
    $output_box .= "\n";
        $output_box .= "<div class='yydev_portfolio " . $main_box_info->slug . " yydev_portfolio_" . $main_box_info->id . $output_box_class . "'" . $container_width_style . ">";
        
        // ================================================
        // Echoing the portfolio pages
        // ================================================
        $items_number = count($wordpress_database_boxes);
        $itesm_per_page = $main_box_info->items_per_page;
        $page_path = get_the_permalink();
        $current_page_number = get_query_var('paged');;

        $output_box .= yydev_portfolio_create_pages($items_number, $itesm_per_page, $page_path, $current_page_number);

        // ================================================
        // Defind the amount of items to display on the page
        // ================================================
        
        // Display only the correct items per page if there are pages
        if(!empty($itesm_per_page)) {

            if( empty($current_page_number) ) {

                // Incase the browser display the first page
                $display_start = 1;
                $display_end = $itesm_per_page;

            } else { // if( empty($current_page_number) ) {

                // If the page display different page then the first page like page 3
                $display_start = ( (($current_page_number-1)*($itesm_per_page) )+1 );
                $display_end = $display_start + ($itesm_per_page-1);

            } // } else { // if( empty($current_page_number) ) {

        } else { // if(!empty($itesm_per_page)) {    

                $display_start = 1;
                $display_end = $items_number;

        }  // } else { // if(!empty($itesm_per_page)) {    

        // ================================================
        // incase the pages were displayed as show all
        // ================================================

        if(  isset($_GET['yy-portfolio']) && sanitize_text_field($_GET['yy-portfolio']) === 'showALL' ) {

            $display_start = 1;
            $display_end = $items_number;

        } // if(  isset($_GET['yy-portfolio']) && sanitize_text_field($_GET['yy-portfolio']) === 'showALL' ) {

        // ================================================
        // Echoing all the boxes images
        // ================================================
        $num = 1;
        foreach($wordpress_database_boxes as $wordpress_info_box) {


            // echoing only the amount allowed on the page
            $not_active_portfolio_image_class = '';
            if( ($display_start <= $num) && ($display_end >= $num) ) { 
                // incase this is active portfolio work
            } else { // if( ($display_start <= $num) && ($display_end >= $num) ) { 
                // incase of inactive portfolio example we will hide it
                $not_active_portfolio_image_class = "hide-portfolio-box ";
            }


                $output_box .= "\n        ";
                
                // ================================================
                // This part will create the open and close url <a> tags
                // ================================================
                $box_url_open = "";
                $box_url_close = "";
                
                $open_new_tab = "";
                if($wordpress_info_box->open_new_tab  == 1) {
                    $open_new_tab = " target='_blank'";
                } // if($wordpress_info_box->open_new_tab  == 1) {
                
                if( !empty($wordpress_info_box->url_link) ) {
                    $box_url_open = "<a" . $open_new_tab . " href='" . $wordpress_info_box->url_link . "'>";
                    $box_url_close = "</a>";
                } // if( !empty($wordpress_info_box->url_link) ) {
            
            
                // ================================================
                // Adding width and height style for the box if it's exists
                // ================================================
            
                $box_style_height_width = "";
                if( !empty($main_box_info->box_height) || !empty($wordpress_info_box->box_height) || !empty($main_box_info->box_width) || !empty($wordpress_info_box->box_width) ) {
                    $box_style_height_width .= "style='";
                    
                    // Setting up the height for the boxes, checking the main box height and then height for each on of the individual boxes
                    if( !empty($main_box_info->box_height) || !empty($wordpress_info_box->box_height) ) {
                        if(!empty($wordpress_info_box->box_height)) {
                            $box_height_value = $wordpress_info_box->box_height;
                        } else { // if(!empty($wordpress_info_box->box_height)) {
                            $box_height_value = $main_box_info->box_height;
                        } // } else { // if(!empty($wordpress_info_box->box_height)) {

                        $box_style_height_width .= "min-height:". $box_height_value . ";";
                    } // if( !empty($main_box_info->box_height) || !empty($wordpress_info_box->box_height) ) {
                    
                    // Setting up the width for the boxes, checking the main box width and then width for each on of the individual boxes
                    if( !empty($main_box_info->box_width) || !empty($wordpress_info_box->box_width) ) {
                        if(!empty($wordpress_info_box->box_width)) {
                            $box_width_value = $wordpress_info_box->box_width;
                        } else { // if(!empty($wordpress_info_box->box_width)) {
                            $box_width_value = $main_box_info->box_width;
                        } // } else { // if(!empty($wordpress_info_box->box_width)) {

                        $box_style_height_width .= "max-width:". $box_width_value . ";";
                    } // if( !empty($main_box_info->box_width) || !empty($wordpress_info_box->box_width) ) {
                    
                    $box_style_height_width .= "'";
                } // if( !empty($main_box_info->box_height) || !empty($main_box_info->box_width) ) {
            
                // echoing the starting <div> tag for each box

                $box_type_class = "";
                if( !empty($main_box_info->box_type) ) {
                    $box_type_class = "wordpress_" . $main_box_info->box_type;
                } // if( !empty($main_box_info->box_type) ) {    
                
                $image_class = "";
                if(!empty($wordpress_info_box->image_class) ) {
                    $image_class = " " . $wordpress_info_box->image_class . " ";
                } // if(!empty($wordpress_info_box->image_class) ) {

                $yydev_box_id_class = "yydev_box_" . $wordpress_info_box->id;
            
                $output_box .= "<div class='yydev_box " . $not_active_portfolio_image_class . $box_type_class . " " . $image_class  . " " . $yydev_box_id_class . "' " . $box_style_height_width . ">";
            
                // ================================================
                // Output the image box if it's exists
                // ================================================
            
                $image_path_link = $wordpress_info_box->image_path;

                if(strstr($image_path_link, ".swf")) {

                    // ================================================
                    // Echoing box if it's a swf file
                    // ================================================
                    $image_path_link_size = getimagesize($image_path_link);
                    $output_box .= "<embed src=\"{$image_path_link}\" width=\"{$image_path_link_size[0]}\" height=\"{$image_path_link_size[1]}\" />";

                } else { // if(strstr($filename, ".swf")) {

                    // ================================================
                    // Echoing box if it's a swf file
                    // ================================================

                    if( !empty($wordpress_info_box->image_path) ) {
                        $get_img_box_link = "<img src='" . $wordpress_info_box->image_path . "' alt='" . htmlspecialchars($wordpress_info_box->image_alt, ENT_QUOTES) . "' />";
                        
                        $output_box .= "<div class='box-image'>";
                        $output_box .=  $box_url_open . "<div class='box-overlay'><span></span></div>" .  $get_img_box_link . $box_url_close;
                        $output_box .= "</div><!--box-image-->";
                        
                    } // if(!empty($wordpress_info_box->image_path) {      

                } // else { // if(strstr($filename, ".swf")) {

                // Closing the box <div>
                $output_box .= "</div><!--yydev_box-->";
            

        $num++;
        } // foreach($main_box_infos as $yydev_portfolioes) {
        
        // ================================================
        // Echoing the pages for the second time
        // ================================================
        $output_box .= "\n\n" . yydev_portfolio_create_pages($items_number, $itesm_per_page, $page_path, $current_page_number, "bottom-pagination");

        // Closing the main box div
        $output_box .= "\n\n";
        $output_box .= "</div><!--yydev_portfolio-->";
        $output_box .= "\n\n";

        if( is_user_logged_in() ) {
            $get_plugin_path_page = get_bloginfo('url') . "/wp-admin/admin.php?page=yydev_portfolio&view=box&id=" .  $box_id;
            $path_plugin_link = "<a class='portfolio-edit-works-button' href='" . $get_plugin_path_page . "'>" . __('Click Here To Edit the Works', 'yydevelopment-portfolio-boxes') . "</a>";

            $output_box .= $path_plugin_link;
            $output_box .= "\n";
        } // if( is_user_logged_in() ) {

    $output_box .= "</div><!--yydev_portfolio_warp-->";
?>