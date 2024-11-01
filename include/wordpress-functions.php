<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php

// ==================================================================
// output the values into the the page or input in the correct way
// allowing to have double and single quotes ", ' inside input
// use $echo = 1 if you want to echo the ouput related_posts_output_string_value($string, 1)
// ==================================================================

function yydev_portfolio_boxes_html_output($output_code, $echo = 0) {

    $output_code = stripslashes_deep($output_code);
    $output_code = esc_html($output_code);

    if($echo == 1) {
        echo $output_code;
    } else { // if($echo == 1) {
        return $output_code;
    } // } else { // if($echo == 1) {
    

} // function yydev_portfolio_boxes_html_output($output_code) {

// ==================================================================
// Creating pages for portfolio pages
// $items_number = the number of items on the portfolio
// $itesm_per_page = the number of items allowed at each page
// ==================================================================

function yydev_portfolio_create_pages($items_number, $itesm_per_page, $page_path, $current_page_number, $add_class="") {

    if(!empty($itesm_per_page)) {

        $portfolio_pages = "";
        
        // Incase there are more items then items allowed for page create pages
        if($items_number > $itesm_per_page) {

            $page_type = '';
            if( isset($page_type) ) {
            $page_type = htmlentities($page_type);
            } // if( isset($page_type) ) {
            
            if(!empty($add_class)) {$add_class = " " . $add_class;}

            $portfolio_pages .= "<div class='pages" . $add_class . "'><p>" . __('Pages:', 'yydevelopment-portfolio-boxes') . " </p>\n";
            // checking how much pages exists
            $start_page = 0;
            $pages_amout = ceil(($items_number / $itesm_per_page));

                while($pages_amout > $start_page) {
                    $page_number = $start_page+1;
                    $portfolio_pages .= "<a ";

                    if( $current_page_number == $page_number ) {$portfolio_pages .= "class='selected' ";}
                    
                    if( $page_number == 1 ){

                            // output regular page url when the page number is 1
                            $portfolio_pages .= "href='{$page_path}'>{$page_number}</a>\n";

                    } else { // if( $page_number == 1 ){

                            // output page url when the page number is bigger than one
                            $portfolio_pages .= "href='" . $page_path . "page/" . $page_number . "/'>{$page_number}</a>\n";

                    } //} else { // if( $page_number == 1 ){
                    
                    $start_page++;
                } // while($pages_amout > $start_page) {

                // ---------------------------------------------------------
                // incase checked all is selected
                // ---------------------------------------------------------
                
                $show_all_checked = '';
                if(  isset($_GET['yy-portfolio']) &&  sanitize_text_field($_GET['yy-portfolio']) === 'showALL' ) { $show_all_checked = "class='selected' "; }
                $portfolio_pages .= "<a {$show_all_checked} href='" . $page_path . "?yy-portfolio=showALL'>" . __('Show All', 'yydevelopment-portfolio-boxes') . "</a>\n";
            
            $portfolio_pages .= "</div><!--pages-->\n";
                    
        } // if($items_number > $itesm_per_page) {
        
        return __($portfolio_pages, 'yydev_portfolio_boxes');

    } // if(!empty($itesm_per_page)) {

} // function yydev_portfolio_create_pages($items_number) {

// ==================================================================
// This function will display error message if there was something wrong
// $error_message will be the name of the string we define and if it's exists
// it will echo the message to the page
// if $display_inline is set to 1 it will have style of display: inline
// ==================================================================

function yydev_portfolio_show_error_message($error_message, $display_inline = "") {
    
    if($display_inline == 1) {
        $display_inline_echo = "display-inline";
    } // if($display_inline == 1) {
    
    if( isset($error_message) ) {
        ?>
        
        <div class="yydev-boxes-error-message <?php echo $display_inline_echo; ?>">
            <?php echo $error_message; ?>
        </div>
        
        <?php
    } // if( isset($error) ) {
    
} // function yydev_portfolio_show_error_message($error) {


// ================================================
// Echoing Message if it's exists 
// ================================================

function yydev_portfolio_echo_boxes_message_if_exists() {
    
    if(isset($_GET['message'])) {
        echo "<div class='wordpress-messsage'> " . htmlentities($_GET['message']) . " </div>";
    } // if(isset($_GET['message'])) {
    
    if(isset($_GET['error-message'])) {
        echo "<div class='wordpress-error-messsage'><b>Error:</b> " .  htmlentities($_GET['error-message']) . " </div>";
    } // if(isset($_GET['error-message'])) {

} // function yydev_portfolio_echo_boxes_message_if_exists() {


function yydev_portfolio_echo_success_message_if_exists($success) {

    if(isset($success) && !empty($success) ) {
        echo "<div class='wordpress-messsage'> " . $success . " </div>";
    } // if(isset($success) && !empty($success) ) {

} // function yydev_portfolio_echo_success_message_if_exists($success) {

function yydev_portfolio_echo_error_message_if_exists($error) {

    if(isset($error) && !empty($error) ) {
        echo "<div class='wordpress-error-messsage'><b>Error:</b> " .  $error . " </div>";
    } // if(isset($_GET['error-message'])) {

} // function yydev_portfolio_echo_error_message_if_exists() {

// ==================================================================
// redirect the page using the path you provided
// ==================================================================

function yydev_portfolio_redirect_page($link) {
	header("Location: {$link}");
	exit;
} // function yydev_portfolio_redirect_page($path) {
