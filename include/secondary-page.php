<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php

$success_message = '';
$post_error_message = '';
$box_name_error = '';

if( isset($_GET['id']) ) {
    $secondary_page_id = intval($_GET['id']);
} // if( isset($_GET['id']) ) {

if( isset($_GET['box-id']) ) {
    $box_id = intval($_GET['box-id']);
} // if( isset($_GET['box-id']) ) {

if( isset($_GET['remove-boxes']) ) {
    $remove_boxes = intval($_GET['remove-boxes']);
} // if( isset($_GET['remove-boxes']) ) {

// ====================================================
// In case the page id was not found
// ====================================================

if( isset($secondary_page_id) && !empty($secondary_page_id) ) {
    
    global $wpdb;
    $check_for_real_box_id = $wpdb->query("SELECT id FROM " . $table_name . " WHERE id = " . $secondary_page_id);
    
    if($check_for_real_box_id == 0 ) {
        $post_error_message = "The box id you were looking for was not found";
        $page_url = esc_url( menu_page_url( 'yydev_portfolio', false ) );
        $new_box_link = $page_url . "&error-message=" . urlencode($post_error_message);
    } // if($check_for_real_box_id < 1 ) {

} // if( (isset($secondary_page_id) && !empty($secondary_page_id)) || !isset($secondary_page_id) ) {
    
// ====================================================
// Removing the box if it was deleted
// ====================================================
    
if( isset($remove_boxes) && isset($box_id) && !empty($box_id) ) {
    
    $check_box_id = $wpdb->query("SELECT id FROM " . $box_table_name . " WHERE id = " . $box_id );
    
    if($check_box_id > 0) {
        // if the box id exists on the database it will be removed
        
        $wpdb->delete( $box_table_name, array('id'=>$box_id) );
        $success_message = "The box id #" . $box_id . " was removed successfully";
        
        $page_url = esc_url( menu_page_url( 'yydev_portfolio', false ) );
        $new_box_link = $page_url . "&view=box&id=" . $secondary_page_id . "&message=" . urlencode($success_message);
        wp_redirect($new_box_link);
        
    } else { // if($check_box_id > 0) {
        
        $post_error_message = "The box id was not found and not deleted";
        $page_url = esc_url( menu_page_url( 'yydev_portfolio', false ) );
        $new_box_link = $page_url . "&view=box&id=" . $secondary_page_id . "&error-message=" . urlencode($post_error_message);
        // yydev_portfolio_redirect_page($new_box_link);
        
    } // } else { // if($check_box_id > 0) {
    
} // if( isset($remove_boxes) && isset($box_id) && !empty($box_id) ) {

// ====================================================
// Add new single box to the database
// ====================================================

if( isset($_POST['yydev_portfolio_nonce_secondary_deta']) ) {

    if( wp_verify_nonce($_POST['yydev_portfolio_nonce_secondary_deta'], 'yydev_portfolio_action_secondary_deta') ) {

        if(isset($secondary_page_id) && !empty($secondary_page_id)) {

            // If someone add new box
            if( empty($_POST['yydev_image_url']) && empty($_POST['yydev_box_title']) && empty($_POST['yydev_box_description']) ) {
                
                // if the person didn't filled the image for the box
                $box_name_error = "You must to at least fill one of the fields: image, title or description in order to submit the box";

            } else { // if( empty($_POST['yydev_image_url']) && empty($_POST['yydev_box_title']) && empty($_POST['yydev_box_description']) ) {

                // ====================================================
                // checking if there is more then one image in the string.
                // if there is more than one it will have comma (,) between urls
                // ====================================================    
                $image_upload_count = count($_POST['yydev_image_url']);

                // using the loop to insert more than one element
                for($count = 0; $count < $image_upload_count; $count++) {

                    // Getting new position number
                    $new_position = 1;
                    $checking_first_position = $wpdb->get_results("SELECT * FROM " . $box_table_name . " WHERE box_id = " . $secondary_page_id . " ORDER BY position ASC limit 1");
                    
                    foreach($checking_first_position as $wordpress_position) {
                        $new_position = $wordpress_position->position / 2;
                    } // foreach($checking_first_position as $wordpress_position) {

                    // ====================================================
                    // Link the image to the parent page in wordpress 
                    // ====================================================
                    $yydev_image_id =  intval($_POST['yydev_image_id'][$count]);
                    $yydev_parent_page_id =  intval($_POST['yydev_parent_page_id'][$count]);
                    if( !empty($yydev_image_id) && !empty($yydev_parent_page_id) ) {
                        wp_update_post( array('ID' => $yydev_image_id, 'post_parent' => $yydev_parent_page_id ) );
                    } // if( !empty($yydev_image_id) && !empty($yydev_parent_page_id) ) {
                    
                    // If there is no error insert the info to the database
                    $yydev_image_url = esc_url_raw($_POST['yydev_image_url'][$count]);
                    $yydev_link_url = esc_url_raw($_POST['yydev_link_url'][$count]);
                    $yydev_box_title = wp_kses_post($_POST['yydev_box_title'][$count]);
                    $yydev_position = floatval($new_position);
                    $yydev_box_alt = sanitize_text_field( $_POST['yydev_box_alt'][$count] );
                    $yydev_box_description = wp_kses_post($_POST['yydev_box_description'][$count]);

                    $yydev_open_new_tab = intval($_POST['yydev_open_new_tab'][$count]);
                    $yydev_nofollow_link = intval($_POST['yydev_nofollow_link'][$count]);

                    $yydev_box_id = intval($secondary_page_id);
                    $yydev_box_width = sanitize_text_field($_POST['yydev_box_width'][$count]);
                    $yydev_box_height = sanitize_text_field($_POST['yydev_box_height'][$count]);
                    $yydev_box_button_text = wp_kses_post($_POST['yydev_box_button_text'][$count]);
                    $yydev_image_class = sanitize_text_field( $_POST['yydev_image_class'][$count]);
                    
                    // Checking if the box id exists
                    $check_if_box_exists = $wpdb->query("SELECT id FROM " . $table_name . " where id = " . $yydev_box_id);
                            
                    if($check_if_box_exists == 0 ) {
                        $box_name_error = "The box id was not found";
                    } else { // if($check_if_box_exists == 0 ) {
                    
                    // If the box id exists it will add the new box
                    $wpdb->insert( $box_table_name,
                        array('box_id'=>$yydev_box_id,
                        'position'=>$yydev_position,
                        'image_path'=>$yydev_image_url,
                        'url_link'=>$yydev_link_url,
                        'title'=>$yydev_box_title,
                        'image_alt'=>$yydev_box_alt,
                        'description'=>$yydev_box_description,
                        'open_new_tab'=>$yydev_open_new_tab,
                        'nofollow_link'=>$yydev_nofollow_link,
                        'box_width'=>$yydev_box_width,
                        'box_height'=>$yydev_box_height,
                        'button_text'=>$yydev_box_button_text,
                        'image_class'=>$yydev_image_class
                        ), array('%d', '%f', '%s', '%s', '%s', '%s', '%s', '%d', '%d', '%s', '%s', '%s', '%s') );
                
                } // for($count = 0; $count < $image_upload_count; $count++) {

                $success_message = "The box was inserted successfully";

                } // } else { // if($check_if_box_exists == 0 ) {
            
            } // } else { // if( empty($_POST['yydev_image_url']) && empty($_POST['yydev_box_title']) && empty($_POST['yydev_box_description']) ) {
            
        } // if(isset($secondary_page_id) && !empty($secondary_page_id)) {

    } else { // if( wp_verify_nonce($_POST['yydev_portfolio_nonce_secondary_deta'], 'yydev_portfolio_action_secondary_deta') ) {
        $post_error_message = "Form nonce was incorrect";
    } // } else { // if( wp_verify_nonce($_POST['yydev_portfolio_nonce_secondary_deta'], 'yydev_portfolio_action_secondary_deta') ) {

} // if( isset($_POST['yydev_portfolio_nonce_secondary_deta']) ) {

// ====================================================
// Update single box if it's changed
// ====================================================

if( isset($_POST['yydev_portfolio_nonce_update_secondary_deta']) ) {

    if( wp_verify_nonce($_POST['yydev_portfolio_nonce_update_secondary_deta'], 'yydev_portfolio_action_update_secondary_deta') ) {
        
        foreach( $_POST['yydev_box_id'] as $this_box_id) {

            $yydev_box_id = intval($_POST['yydev_box_id'][$this_box_id]);

            if( !empty($yydev_box_id) ) { 

                // If there is no error insert the info to the database
                $yydev_image_url = esc_url_raw($_POST['yydev_image_url'][$this_box_id]);
                $yydev_link_url = esc_url_raw($_POST['yydev_link_url'][$this_box_id]);
                $yydev_box_title = wp_kses_post($_POST['yydev_box_title'][$this_box_id]);
                $yydev_position = floatval($_POST['yydev_position'][$this_box_id]);
                $yydev_box_alt = sanitize_text_field( $_POST['yydev_box_alt'][$this_box_id]);
                $yydev_box_description = wp_kses_post($_POST['yydev_box_description'][$this_box_id]);

                $yydev_open_new_tab = intval($_POST['yydev_open_new_tab'][$this_box_id]);
                $yydev_nofollow_link = intval($_POST['yydev_nofollow_link'][$this_box_id]);

                $yydev_box_width = sanitize_text_field($_POST['yydev_box_width'][$this_box_id]);
                $yydev_box_height = sanitize_text_field($_POST['yydev_box_height'][$this_box_id]);
                $yydev_box_button_text = wp_kses_post($_POST['yydev_box_button_text'][$this_box_id]);
                $yydev_image_class = sanitize_text_field( $_POST['yydev_image_class'][$this_box_id]);
                
                // Checking if the box id exists
                $check_if_box_exists = $wpdb->query("SELECT id FROM " . $box_table_name . " where id = " . $yydev_box_id);
                        
                if($check_if_box_exists == 0 ) {
                    $post_error_message = "The box id was not found";
                } else { // if($check_if_box_exists < 1 ) {
                
                // If the box id exists it will add the new box
                $wpdb->update( $box_table_name,
                    array('image_path'=>$yydev_image_url,
                    'position'=>$yydev_position,
                    'url_link'=>$yydev_link_url,
                    'title'=>$yydev_box_title,
                    'image_alt'=>$yydev_box_alt,
                    'description'=>$yydev_box_description,
                    'open_new_tab'=>$yydev_open_new_tab,
                    'nofollow_link'=>$yydev_nofollow_link,
                    'box_width'=>$yydev_box_width,
                    'box_height'=>$yydev_box_height,
                    'button_text'=>$yydev_box_button_text,
                    'image_class'=>$yydev_image_class
                    ), array('id'=>$yydev_box_id), array('%s', '%f', '%s', '%s', '%s', '%s', '%d', '%d', '%s', '%s', '%s', '%s') );

                    $success_message = "The box was updated successfully";

                } // } else { // if($check_if_box_exists < 1 ) {

            } // if( !empty($yydev_box_id) ) { 

        } // foreach( $_POST['yydev_box_id'] as $this_box_id) {

    } else { // if( wp_verify_nonce($_POST['yydev_portfolio_nonce_update_secondary_deta'], 'yydev_portfolio_action_update_secondary_deta') ) {
        $post_error_message = "Form nonce was incorrect";
    } // } else { // if( wp_verify_nonce($_POST['yydev_portfolio_nonce_update_secondary_deta'], 'yydev_portfolio_action_update_secondary_deta') ) {

} // if( isset($_POST['yydev_portfolio_nonce_update_secondary_deta']) ) {

// ====================================================
// Update the main box container if it's changed
// ====================================================
    
if( isset($_POST['yydev_portfolio_nonce_edit_main_db']) ) {

    if( wp_verify_nonce($_POST['yydev_portfolio_nonce_edit_main_db'], 'yydev_portfolio_action_edit_main_db') ) {

        $yydev_box_id = intval($_POST['yydev_box_id']);

        if( !empty($_POST['yydev_box_id']) ) {

                // If there is no error insert the info to the database
                
                $parent_page_id = intval($_POST['parent_page_id']);
                $items_per_page = intval($_POST['items_per_page']);
                $yydev_box_name = sanitize_text_field($_POST['yydev_box_name']);
                $yydev_box_slug = sanitize_text_field( $_POST['yydev_box_slug']);
                $yydev_container_width = sanitize_text_field($_POST['yydev_container_width']);
                $yydev_box_width = sanitize_text_field($_POST['yydev_box_width']);
                $yydev_box_height = sanitize_text_field($_POST['yydev_box_height']);
                $yydev_box_button_text = sanitize_text_field($_POST['yydev_box_button_text']);
                $yydev_box_type = sanitize_text_field($_POST['yydev_box_type']);

                $order_by = '';
                if(isset($_POST['order_by'])) {
                    $order_by = floatval($_POST['order_by']);
                } // if(isset($_POST['order_by'])) {

                $hidden_load = '';
                if( isset($_POST['hidden_load']) ) {
                    $hidden_load = intval($_POST['hidden_load']);
                } // if( isset($_POST['hidden_load']) ) {

                $yydev_adding_class = intval($_POST['yydev_adding_class']);

                // Checking if the box id exists
                $check_if_box_exists = $wpdb->query("SELECT id FROM " . $table_name . " where id = " . $yydev_box_id);

                if($check_if_box_exists == 0 ) {
                    $post_error_message = "The box id was not found";
                } else { // if($check_if_box_exists < 1 ) {
                
                // If the box id exists it will add the new box
                $wpdb->update( $table_name,
                    array('parent_page_id'=>$parent_page_id,
                    'items_per_page'=>$items_per_page,
                    'name'=>$yydev_box_name,
                    'slug'=>$yydev_box_slug,
                    'container_width'=>$yydev_container_width,
                    'box_width'=>$yydev_box_width,
                    'box_height'=>$yydev_box_height,
                    'button_text'=>$yydev_box_button_text,
                    'box_type'=>$yydev_box_type,
                    'order_by'=>$order_by,
                    'hidden_load'=>$hidden_load,
                    'allow_class'=>$yydev_adding_class,
                    ), array('id'=>$yydev_box_id), array('%d', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d', '%d') );
                
                    // Creating page link and redirect the user to the new url page where he can edit the box
                    $page_url = esc_url( menu_page_url( 'yydev_portfolio', false ) );
                    $new_detabase_id = $wpdb->insert_id;
                    $success_message = "The box was updated successfully";
                    $new_box_link = $page_url . "&view=box&id=" . $yydev_box_id . "&message=" . urlencode($success_message);
                    // yydev_portfolio_redirect_page($new_box_link);

                } // } else { // if($box_name_exists_check > 0 ) {
                
        } // if( !empty($_POST['yydev_box_id']) ) {
             
    } else { // if( wp_verify_nonce($_POST['yydev_portfolio_nonce_edit_main_db'], 'yydev_portfolio_action_edit_main_db') ) {
        $post_error_message = "Form nonce was incorrect";
    } // } else { // if( wp_verify_nonce($_POST['yydev_portfolio_nonce_edit_main_db'], 'yydev_portfolio_action_edit_main_db') ) {

} // if( isset($_POST['yydev_portfolio_nonce_edit_main_db']) ) {

?>

<div class="wrap yydev_box_main" style="direction:ltr;">
    <h2 class="yydev-display-inline">Wordpress Edit Box</h2>
    <a href="<?php echo esc_url( menu_page_url( 'yydev_portfolio', false ) ); ?>">Go Back</a>

    <?php yydev_portfolio_echo_boxes_message_if_exists(); ?>
    <?php yydev_portfolio_echo_success_message_if_exists($success_message); ?>
    <?php yydev_portfolio_echo_error_message_if_exists($post_error_message); ?>
    
    <div class="insert-new-box">
        
<?php
    // Getting the box info from the database
    $check_box_id = $wpdb->get_row("SELECT * FROM " . $table_name . " WHERE id = " . $secondary_page_id );

    // ====================================================
    // Checking which box type is loaded and load the setting
    // file from the boxes-templates folder 
    // ====================================================

    $box_type_name = $check_box_id->box_type;
    $box_setting_url = "boxes-templates/" . $box_type_name . "/settings.php";
    $box_setting_full_url = dirname( __FILE__ ) . '/boxes-templates/' . $box_type_name . '/settings.php';
    
    if(file_exists($box_setting_full_url)) {    
        include($box_setting_url);
    } else { // if(file_exists($box_setting_url)) {
    
    // ====================================================
    // Choosing which elements to display and which not to The settings below will allow hide or display fields on the page depending on the box type.
    // if you give value 1 it will display the input if you give value 0 it will not display it
    // ====================================================
    
    // Echoing this section only if settings.php file not exists 
    $box_button_text = 1; // Allow to display "Boxes Button Text"
    $box_upload_image = 1; // Allow to upload image for the boxes
    $box_image_alt = 1; // Allow to add image alt tag
    $box_description_text = 1; // Allow to display "Box Description" text 
    $box_title_text = 1; // Allow to display the "Box Title" text 
    $box_width_value = 1; // Allow to choose width for the boxes "Box Width" 
    $box_height_value  = 1; // Allow to choose height for the boxes "Box height" 
    $box_url_link = 1; // allow to choose link to the boxes
    
    } // } else { // if(file_exists($box_setting_url)) {
    
?>

        <h4>Box Output Info</h4>

        <p> <b>Shortcode</b> You can use this code inside blog posts and pages (simply copy the code inside the post)
        <br />
        <span class="output-code">[wordpress-boxes id="<?php echo $check_box_id->id;?>" name="<?php echo $check_box_id->slug;?>" limit="0"]</span>
        </p>
        
        <h4>Edit Box Settings</h4>
                
        <form class="edit-boxes" method="POST" action="">
           
            <span>Box Id: <?php echo $check_box_id->id; ?></span>
            <br />

            <label for="parent_page_id">Box Parent Page ID:</label>
            <input type="text" id="parent_page_id" class="shorter_input" name="parent_page_id" value="<?php echo $check_box_id->parent_page_id; ?>" />
            <small>This ID will link the image to the portfolio page that we posting the images on. We can find the id by going to the page and checking the link.</small>
            <br />

            <label for="items_per_page">Items Per Page:</label>
            <input type="text" id="items_per_page" class="shorter_input" name="items_per_page" value="<?php echo $check_box_id->items_per_page; ?>" />
            <small>This field will define how many images will show up on each page. Leave at 0 if you don't want pagination for the page.</small>
            <br />
                       
            <label for="yydev_box_type">Box Type:</label>
            <select id="yydev_box_type" class="yydev_box_type" name="yydev_box_type">
<?php
    // ====================================================
    // Output only the exists boxes to the page
    // ====================================================
    include("boxes-templates/box-type.php");
    
    if(isset($box_type_output)) {
        
        // outputing the select options from box-type.php
        foreach($box_type_output as $box_type_select) {
                $box_type_value = $box_type_select['box_type_value'];
                $box_type_name = $box_type_select['box_type_name'];
?>
                <option value="<?php echo $box_type_value; ?>" <?php if($check_box_id->box_type == $box_type_value) {echo "selected='selected'";} ?>><?php echo $box_type_name; ?></option>
<?php
        } // foreach($box_type_output as $box_type_select) {
        
    } // f(isset($box_type_output)) {
?>
            </select>
           
           <br />
            <label for="yydev_box_name">Box Name:</label>
            <input type="text" id="yydev_box_name" class="yydev_box_name" name="yydev_box_name" value="<?php yydev_portfolio_boxes_html_output($check_box_id->name, 1); ?>" />
            
            <br />
            <label for="yydev_box_name">Box Slug \ Class Name:</label>
            <input type="text" id="yydev_box_slug" class="yydev_box_slug" name="yydev_box_slug" value="<?php yydev_portfolio_boxes_html_output($check_box_id->slug, 1); ?>" />
            <small>(The slug used for the box class name) &nbsp;&nbsp;&nbsp; This box class name is: <b><?php echo $check_box_id->slug; ?></b></small> 
            
            <br />
            <label for="yydev_container_width">Container Width: </label>
            <input type="text" id="yydev_container_width" class="yydev_container_width shorter_input" name="yydev_container_width" value="<?php yydev_portfolio_boxes_html_output($check_box_id->container_width, 1); ?>" />
            <small>(e.g. 200px) (The width of all the boxes section, Leave empty for full width)</small>

            <br />
            <label for="yydev_adding_class">Allow Adding Image Class: </label>
            <select id="yydev_adding_class" class="yydev_adding_class" name="yydev_adding_class">
            <option value="0" <?php if($check_box_id->allow_class == 0) {echo "selected='selected'";} ?>>No</option>
            <option value="1" <?php if($check_box_id->allow_class == 1) {echo "selected='selected'";} ?>>Yes</option>
            <select>
            <small>By chossing yes you will have the option to add classes to each image below</small>

            <br />
            <input type="checkbox" id="hidden_load" class="hidden_load checkbox" name="hidden_load" value="1" <?php if($check_box_id->hidden_load ==1) {echo "checked";} ?> />
            <label for="hidden_load">Load all elements to the page and hide them with with css</label>

            <div class="input-block <?php if($box_width_value == 0) {echo "hidden-input";} ?>">
            <br />
            <label for="yydev_box_width">Box Width: </label>
            <input type="text" id="yydev_box_width" class="yydev_box_width shorter_input" name="yydev_box_width" value="<?php yydev_portfolio_boxes_html_output($check_box_id->box_width, 1); ?>" />
            <small>(e.g. 200px) (The width for each box. Recommended value: 275px)</small>
            </div><!--input-block-->
            
            <div class="input-block <?php if($box_height_value == 0) {echo "hidden-input";} ?>">
            <br />
            <label for="yydev_box_height">Minimum Box Height: </label>
            <input type="text" id="yydev_box_height" class="yydev_box_height shorter_input" name="yydev_box_height" value="<?php yydev_portfolio_boxes_html_output($check_box_id->box_height, 1); ?>" />
            <small>(e.g. 200px) (The height for each one of the boxes, recommend to set if you have one box bigger then the other You can only use it to make the height bigger and not smaller)</small>
            </div><!--input-block-->
            
            <div class="input-block <?php if($box_button_text == 0) {echo "hidden-input";} ?>">
            <br />
            <label for="yydev_box_button_text">Boxes Button Text: </label>
            <input type="text" id="yydev_box_button_text" class="yydev_box_button_text" name="yydev_box_button_text" value="<?php yydev_portfolio_boxes_html_output($check_box_id->button_text, 1); ?>" />
            <small>(e.g. Click Here) (Giving button name to all the boxes, leave empty if you don't want button name)</small>
            </div><!--input-block-->

            <input type="hidden" name="yydev_box_id" class="yydev_box_id" value="<?php echo $secondary_page_id; ?>" />

            <?php wp_nonce_field( 'yydev_portfolio_action_edit_main_db', 'yydev_portfolio_nonce_edit_main_db' ); ?>

            <br /><br />
            <input type="submit" class="edit-boxes button-cursor" name="edit-boxes" value="Edit Box" />
        </form>
     

<?php
    // ===============================================
    // This will enqueue the Media Uploader script
    // ===============================================
    wp_enqueue_media();
?>

<form class="insert-box" method="POST" action="">
    
    <br /><br />
    <h2>Add Boxes</h2> 
    <small>(Make sure to insert the image of all the boxes at the same size)</small>
    <br /><br />
    
    <table id="add_boxes_table_warp" class="add_boxes_table">
    <tr>
        <td style="width:300px;vertical-align:top;text-align: center;padding-right:20px;" class="<?php if($box_upload_image == 0) {echo "hidden-input";} ?>">
            <img id="add_boxes_table" class="img_url_button yydev-box-image add-button-image-upload image_url" src="<?php echo plugins_url( 'images/add-image.png', dirname(__FILE__) ); ?>" alt="" />
        </td>
    
        <td>
            <?php yydev_portfolio_show_error_message($box_name_error, '1'); ?>
            
            <div class="input-block">
            <br />
            <label for="yydev_image_id">Image ID</label>
            <input type="text" id="yydev_image_id" class="yydev_image_id shorter_input" name="yydev_image_id[]" value="" />
            <input type="hidden" class="shorter_input" name="yydev_parent_page_id[]" value="<?php echo $check_box_id->parent_page_id; ?>" />
            <small>The ID wordpress assign to the image when it created. We use it in order to assign the image to the page.</small>
            </div><!--input-block-->
            
            <div class="upload-images <?php if($box_upload_image == 0) {echo "hidden-input";} ?>">
                <label for="image_url">Image</label>
                <input type="text" name="yydev_image_url[]" class="image_url" >
                <input type="button" name="upload-btn[]" id="image_url" class="add-button-image-upload button-secondary medium-size-input" value="Choose Image...">
            </div><!--upload-images-->

             <div class="input-block <?php if($box_image_alt == 0) {echo "hidden-input";} ?>">
            <label for="yydev_box_alt">Image Alt Tag:</label>
            <input type="text" id="yydev_box_alt" class="yydev_box_alt medium-size-input" name="yydev_box_alt[]" id="yydev_box_alt" value="" />
            <small>The alt tag for the image (optional)</small>
            </div><!--input-block-->

             <div class="input-block <?php if($box_title_text == 0) {echo "hidden-input";} ?>">
             <br />
            <label for="yydev_box_title">Box Title:</label>
            <input type="text" id="yydev_box_title" class="yydev_box_title" name="yydev_box_title[]" value="" />
            <small>The title for the box (optional)</small>
            </div><!--input-block-->
            
            <div class="input-block <?php if($box_description_text == 0) {echo "hidden-input";} ?>">
            <br />
            <label for="yydev_box_description">Box Description:</label>
            <textarea name="yydev_box_description[]" id="yydev_box_description" class="yydev_box_description" rows="5" cols="60"></textarea>
            <small>The description for the box (optional)</small>
            </div><!--input-block-->
            
            <div class="input-block <?php if($box_url_link == 0) {echo "hidden-input";} ?>">
            <br />
            <label for="yydev_link_url">Link URL</label>
            <input type="text" id="yydev_link_url" class="yydev_link_url medium-size-input" name="yydev_link_url[]" value="" />
            <small>The link will redirect the user to the page you choose when he click on the box (e.g. http://www.site.com) (optional) </small>
            <br />
            <input type="checkbox" id="yydev_open_new_tab" class="yydev_open_new_tab checkbox" name="yydev_open_new_tab[]" value="1" />
            <label for="yydev_open_new_tab"class="yydev_open_new_tab_label">Open link in new tab</label>

            &nbsp;&nbsp;
            <input type="checkbox" id="yydev_nofollow_link" class="yydev_nofollow_link checkbox" name="yydev_nofollow_link[]" value="1" />
            <label for="yydev_nofollow_link"class="yydev_nofollow_link">Mark link as nofollow</label>
            </div><!--input-block-->
            
            <br />
            <div class="input-block <?php if($box_width_value == 0) {echo "hidden-input";} ?>">
            <label for="yydev_box_width_add">Box Width: </label>
            <input type="text" id="yydev_box_width_add" class="yydev_box_width shorter_input" name="yydev_box_width[]" value="" />
            </div><!--input-block-->
            
            <div class="input-block <?php if($box_height_value == 0) {echo "hidden-input";} ?>">
            <label for="yydev_box_height_add">Box Height: </label>
            <input type="text" id="yydev_box_height_add" class="yydev_box_height shorter_input" name="yydev_box_height[]" value="" />
            <small>(e.g. 200px) (Leave Empty If you want all the boxes to be the same)</small>
            </div><!--input-block-->
            
            <?php
                if($check_box_id->allow_class == 1) {
            ?>
                <br />
                <label for="input-block"> Image Class</label>
                <input type="text" id="yydev_image_class" class="yydev_image_class" name="yydev_image_class[]" value="" />
                <small>Define here the image class you want to set</small>
            <?php
                } // if($check_box_id->allow_class == 1) {
            ?>

            <div class="input-block <?php if($box_button_text == 0) {echo "hidden-input";} ?>">
            <br />
            <label for="yydev_box_button_text_add">Boxes Button Text: </label>
            <input type="text" id="yydev_box_button_text_add" class="yydev_box_button_text" name="yydev_box_button_text[]" value="" />
            <small>(e.g. Click Here) (Giving button name to just this box)</small>
            </div><!--input-block-->
            
            <input type="hidden" name="box_id[]" value="<?php echo $secondary_page_id; ?>" />
            
            <br /><br />
        
        </td>
        
    </tr>
    </table>

    <?php
        // creating nonce to make sure the form was submitted correctly from the right page
        wp_nonce_field( 'yydev_portfolio_action_secondary_deta', 'yydev_portfolio_nonce_secondary_deta' ); 
    ?>

    <input type="submit" name="submit-new-box" class="button-cursor submit-all-boxes" value="Submit Portfolio Boxes" />
    <div class="clear"></div>

</form>

    
    <br /><br /><br />
    <h2>Edit Boxes</h2>     
    
<form method="POST" action="">
            
    <table class="wp-list-table widefat fixed striped posts boxes-table">
    <thead>
        <tr>
            <th style="width:20px;">ID</th>
            <th style="width:300px;" class="<?php if($box_upload_image == 0) {echo "hidden-input";} ?>" >Box Image</th>
            <th style="width:450px;">Info</th>
            <th style="width:60px;">Remove</th>
        </tr>
    </thead>
    
    <tbody id="the-list">
    
<?php
    
// ================================================
// Echoing all the boxes from the database 
// ================================================

    $wordpress_database_boxes = $wpdb->get_results("SELECT * FROM " . $box_table_name . " WHERE box_id = " . $secondary_page_id . " ORDER BY position ASC");
    
    // Echo there is no boxes if they are not in there
    if(empty($wordpress_database_boxes)) {
?>
     <tr class="no-items"><td class="colspanchange" colspan="4">No Boxes found</td></tr>
<?php     
    } // if(empty($wordpress_database_boxes)) {
    
    
    $position_number = 1;
    foreach($wordpress_database_boxes as $yydev_portfolioes) {
    
    $page_url = esc_url( menu_page_url( 'yydev_portfolio', false ) );        
    
// $yydev_portfolioes->id
// $yydev_portfolioes->box_id
// $yydev_portfolioes->image_path
// $yydev_portfolioes->url_link
// $yydev_portfolioes->title
// $yydev_portfolioes->description

$box_id = $yydev_portfolioes->id;;

?>

            <tr>
     
                <td><?php echo $position_number; ?></td>
                <td class="<?php if($box_upload_image == 0) {echo "hidden-input";} ?>">
                    <div class="left-img-warp">
                        <img id="image_url<?php echo $yydev_portfolioes->id; ?>" class="img_url_button yydev-box-image edit-button-image-upload image_url<?php echo $yydev_portfolioes->id; ?>" src="<?php echo $yydev_portfolioes->image_path; ?>" alt="" /><br />
                    </div><!--left-img-warp-->

                    <div class="upload-images">
                        <label for="image_url">Image</label>
                        <input type="text" name="yydev_image_url[<?php echo $box_id; ?>]" class="image_url<?php echo $yydev_portfolioes->id; ?> edit-image-url" value="<?php echo yydev_portfolio_boxes_html_output($yydev_portfolioes->image_path); ?>"><br />
                        <input type="button" name="upload-btn" id="image_url<?php echo $yydev_portfolioes->id; ?>" class="edit-button-image-upload button-secondary" value="Choose Diffrent Image...">
                    </div><!--upload-images-->
                </td>
                
                <td style="vertical-align:middle;">

                    <div class="input-block <?php if($box_image_alt == 0) {echo "hidden-input";} ?>">
                    <label for="yydev_box_alt_<?php echo $yydev_portfolioes->id; ?>">Image Alt Tag:</label>
                    <input type="text" id="yydev_box_alt_<?php echo $yydev_portfolioes->id; ?>" class="yydev_box_alt edit-image-url-shorter" name="yydev_box_alt[<?php echo $box_id; ?>]" value="<?php echo yydev_portfolio_boxes_html_output($yydev_portfolioes->image_alt); ?>" />
                    </div><!--input-block-->

                    <div class="input-block <?php if($box_title_text == 0) {echo "hidden-input";} ?>">
                    <br />  
                    <label for="yydev_box_title_<?php echo $yydev_portfolioes->id; ?>">Box Title:</label>
                    <input type="text" id="yydev_box_title_<?php echo $yydev_portfolioes->id; ?>" class="yydev_box_title" name="yydev_box_title[<?php echo $box_id; ?>]" value="<?php echo yydev_portfolio_boxes_html_output($yydev_portfolioes->title); ?>" />
                    </div><!--input-block-->
                    
                    <div class="input-block <?php if($box_description_text == 0) {echo "hidden-input";} ?>">
                    <br />   
                    <label for="yydev_box_description_<?php echo $yydev_portfolioes->id; ?>">Box Description:</label><br />
                    <textarea name="yydev_box_description[<?php echo $box_id; ?>]" id="yydev_box_description_<?php echo $yydev_portfolioes->id; ?>" class="yydev_box_description" rows="5" cols="60"><?php echo yydev_portfolio_boxes_html_output($yydev_portfolioes->description); ?></textarea>
                    </div><!--input-block-->
                    
                    <div class="input-block <?php if($box_url_link == 0) {echo "hidden-input";} ?>">
                    <br />
                    <label for="yydev_link_url_<?php echo $yydev_portfolioes->id; ?>">Link URL</label>
                    <input type="text" id="yydev_link_url_<?php echo $yydev_portfolioes->id; ?>" class="yydev_link_url edit-image-url-shorter" name="yydev_link_url[<?php echo $box_id; ?>]" value="<?php echo $yydev_portfolioes->url_link; ?>" />
                    <br />
                    <input type="checkbox" id="yydev_open_new_tab-<?php echo $yydev_portfolioes->id; ?>" class="yydev_open_new_tab checkbox" name="yydev_open_new_tab[<?php echo $box_id; ?>]" value="1" <?php if($yydev_portfolioes->open_new_tab ==1) {echo "checked";} ?> />
                    <label for="yydev_open_new_tab-<?php echo $yydev_portfolioes->id; ?>">Open link in new tab</label>

                    &nbsp;&nbsp;
                    <input type="checkbox" id="yydev_nofollow_link-<?php echo $yydev_portfolioes->id; ?>" class="yydev_nofollow_link checkbox" name="yydev_nofollow_link[<?php echo $box_id; ?>]" value="1" <?php if($yydev_portfolioes->nofollow_link ==1) {echo "checked";} ?> />
                    <label for="yydev_nofollow_link-<?php echo $yydev_portfolioes->id; ?>">Mark link as nofollow</label>
                    </div><!--input-block-->
                    
                    <br />
                    <div class="input-block <?php if($box_width_value == 0) {echo "hidden-input";} ?>">
                    <label for="yydev_box_width_<?php echo $yydev_portfolioes->id; ?>">Box Width: </label>
                    <input type="text" id="yydev_box_width_<?php echo $yydev_portfolioes->id; ?>" class="yydev_box_width shorter_input" name="yydev_box_width[<?php echo $box_id; ?>]" value="<?php echo yydev_portfolio_boxes_html_output($yydev_portfolioes->box_width); ?>" />
                    </div><!--input-block-->
                    
                    <div class="input-block <?php if($box_height_value == 0) {echo "hidden-input";} ?>">
                    <label for="yydev_box_height_<?php echo $yydev_portfolioes->id; ?>">Box Height: </label>
                    <input type="text" id="yydev_box_height_<?php echo $yydev_portfolioes->id; ?>" class="yydev_box_height shorter_input" name="yydev_box_height[<?php echo $box_id; ?>]" value="<?php echo yydev_portfolio_boxes_html_output($yydev_portfolioes->box_height); ?>" />
                    <small>(e.g. 200px) (Leave Empty If you want all the boxes to be the same)</small>
                    </div><!--input-block-->
                    
                    <div class="input-block <?php if($box_button_text == 0) {echo "hidden-input";} ?>">
                    <br />
                    <label for="yydev_box_button_text_<?php echo $yydev_portfolioes->id; ?>">Boxes Button Text: </label>
                    <input type="text" id="yydev_box_button_text_<?php echo $yydev_portfolioes->id; ?>" class="yydev_box_button_text" name="yydev_box_button_text[<?php echo $box_id; ?>]" value="<?php echo yydev_portfolio_boxes_html_output($yydev_portfolioes->button_text); ?>" />
                    <small>(e.g. Click Here) (Giving button name to just this box)</small>
                    </div><!--input-block-->

                    <br />
                    <div class="input-block">
                    <label for="yydev_box_width_<?php echo $yydev_portfolioes->id; ?>">Position: </label>
                    <input type="text" id="yydev_box_width_<?php echo $yydev_portfolioes->id; ?>" class="yydev_box_width shorter_input" name="yydev_position[<?php echo $box_id; ?>]" value="<?php echo yydev_portfolio_boxes_html_output($position_number); ?>" />
                    </div><!--input-block-->

                    &nbsp;&nbsp;&nbsp;
                    <?php
                        if($check_box_id->allow_class == 1) {
                    ?>
                            <label for="yydev_image_class_<?php echo $yydev_portfolioes->id; ?>">Image Class</label>
                            <input type="text" id="yydev_image_class_<?php echo $yydev_portfolioes->id; ?>" class="yydev_image_class" name="yydev_image_class[<?php echo $box_id; ?>]" value="<?php echo $yydev_portfolioes->image_class; ?>" />
                    <?php
                        } // if($check_box_id->allow_class == 1) {
                    ?>
                    
                </td>
                
                
                <td style="vertical-align:middle;">
                    <a class="remove-box-image" href="<?php echo $page_url . "&view=box&remove-boxes=1&id=" . $secondary_page_id . "&box-id=" . $yydev_portfolioes->id; ; ?>">
                        <img  src="<?php echo plugins_url( 'images/delete-box.png', dirname(__FILE__) ); ?>" alt="Remove Box" title="Remove Box" />
                       
                    </a>
                </td>
    
                <input type="hidden" name="yydev_box_id[<?php echo $box_id; ?>]" value="<?php echo $yydev_portfolioes->id; ?>" />
                
            </tr>
            
<?php
    $position_number++;
    } // foreach($wordpress_database_boxes as $yydev_portfolioes) {
    
?>

    </tbody>
    
    <tfoot>
        <tr>
            <th style="width:20px;">ID</th>
            <th style="width:300px;" class="<?php if($box_upload_image == 0) {echo "hidden-input";} ?>" >Box Image</th>
            <th style="width:450px;">Info</th>
            <th style="width:60px;">Remove</th>
        </tr>
    </tfoot>
    
    </table>
        
<?php
    // creating nonce to make sure the form was submitted correctly from the right page
    wp_nonce_field( 'yydev_portfolio_action_update_secondary_deta', 'yydev_portfolio_nonce_update_secondary_deta' ); 
?>

<input type="submit" class="update-box" id="yydev-update-box" name="update-box" value="Update All Boxes"> </td>

</form>


<a class="yydev-remove-box remove-box-button" href="
<?php
$page_url = esc_url( menu_page_url( 'yydev_portfolio', false ) );
echo $page_url . "&view=box&remove-box=1&id=" . $secondary_page_id; ?>
">Delete Box</a>

<br />
<span id="footer-thankyou-code">This plugin was create by <a target="_blank" href="https://www.yydevelopment.com">YYDevelopment</a>. If you liked the plugin please give it a <a target="_blank" href="https://wordpress.org/plugins/yydevelopment-portfolio-boxes/#reviews">5 stars review</a>.
If you want to help support this FREE plugin <a target="_blank" href="https://www.yydevelopment.com/coffee-break/?plugin=yydevelopment-portfolio-boxes">buy us a coffee</a>.</span>
</span>
<br />

</div><!--wrap yydev_box_main-->