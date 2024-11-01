<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php

$success_message = '';
$post_error_message = '';
$box_name_error = '';

if( isset($_GET['id']) ) {
    $secondary_page_id = intval($_GET['id']);
} // if( isset($_GET['id']) ) {

if( isset($_GET['remove-box']) ) {
    $remove_box = intval($_GET['remove-box']);
} // if( isset($_GET['remove-box']) ) {

$plugin_page_url = esc_url( menu_page_url( 'yydev_portfolio', false ) );

// ====================================================
// Inserting the box to the database if it was created
// ====================================================

if( isset($_POST['yydev_portfolio_nonce_main']) ) {

    if( wp_verify_nonce($_POST['yydev_portfolio_nonce_main'], 'yydev_portfolio_action_main') ) {

        if( isset($_POST['submit-new-box'])  ) {
            // If someone create new box    
            
            if( empty($_POST['yydev_box_name']) ) {
                // If the box name is empty echo a message
                $box_name_error = "You have to give a name for the box";

            } else { // if( !empty($_POST['yydev_box_name']) ) {
                
                // If there is no error insert the info to the database

                $yydev_parent_page_id = intval($_POST['yydev_parent_page_id']);
                $yydev_box_type = sanitize_text_field($_POST['yydev_box_type']);
                
                $box_name = sanitize_text_field($_POST['yydev_box_name']);

                $box_slug_name = str_replace(" ", "_", strtolower( trim($box_name) ) );
                $box_slug_name = sanitize_text_field($box_slug_name);


                // Checking if the box name already exists
                $box_name_exists_check = $wpdb->query("SELECT slug FROM " . $table_name . " WHERE slug = '{$box_slug_name}' ");
                        
                        
                if($box_name_exists_check > 0 ) {
                    $box_name_error = "The box slug name is already exists please choose different name";
                } else { // if($box_name_exists_check > 0 ) {
                
                // If the box name not exists it will insert it into the database
                $wpdb->insert( $table_name,
                    array('parent_page_id'=>$yydev_parent_page_id,
                    'name'=>$box_name,
                    'slug'=>$box_slug_name,
                    'container_width'=>'',
                    'box_width'=>'',
                    'box_height'=>'',
                    'box_type'=>$yydev_box_type,
                    'hidden_load'=> 1,
                    'allow_class'=> 1,
                    ), array('%d', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d') );
                
                    // Creating page link and redirect the user to the new url page where he can edit the box
                    $new_detabase_id = $wpdb->insert_id;
                    $new_page_link = $plugin_page_url . "&view=secondary&id=" . $new_detabase_id;
                    $success_message = "The portfolio box was successfully created to view it <a href='" . $new_page_link . "'>click here</a>";
                
                } // } else { // if($box_name_exists_check > 0 ) {
            
            } // if( !empty($_POST['yydev_box_name']) ) {
            
        } // if( isset($_POST['submit-new-box']) ) {

    } else { // if( wp_verify_nonce($_POST['yydev_portfolio_nonce_main'], 'yydev_portfolio_action_main') ) {
        $post_error_message = "Form nonce was incorrect";
    } // } else { // if( wp_verify_nonce($_POST['yydev_portfolio_nonce_main'], 'yydev_portfolio_action_main') ) {

} // if( isset($_POST['yydev_portfolio_nonce_main']) ) {

// ====================================================
// Removing the main box and sub boxes if it was deleted
// ====================================================

if( isset($_POST['yydev_portfolio_nonce_remove']) ) {

    if( wp_verify_nonce($_POST['yydev_portfolio_nonce_remove'], 'yydev_portfolio_action_remove') ) {

    $secondary_page_id = '';
    if( isset($_POST['remove_portfolio_id']) ) {
        $secondary_page_id = intval($_POST['remove_portfolio_id']);
    } // if( isset($_POST['remove_portfolio_id']) ) {

    if( isset($secondary_page_id) && !empty($secondary_page_id) ) {

        $check_box_id = $wpdb->query("SELECT * FROM " . $table_name . " WHERE id = " . $secondary_page_id );
        
        // Removing the boxes from the database
        $wpdb->delete( $box_table_name, array('box_id'=>$secondary_page_id) );
        
        if($check_box_id > 0) {
            // if the box id exists on the database it will be removed
            
            $wpdb->delete( $table_name, array('id'=>$secondary_page_id) );
            $success_message = "The box id #" . $secondary_page_id . " was removed successfully";
            
            $page_url = esc_url( menu_page_url( 'yydev_portfolio', false ) );
            $new_box_link = $page_url . "&message=" . urlencode($success_message);
            // yydev_portfolio_redirect_page($new_box_link);
            
        } else { // if($check_box_id > 0) {
            $post_error_message = "The box id was not found";
        } // } else { // if($check_box_id > 0) {

    } // } // if( isset($secondary_page_id) && !empty($secondary_page_id) ) {
    
    } else { // if( wp_verify_nonce($_POST['yydev_portfolio_nonce_remove'], 'yydev_portfolio_action_remove') ) {
        $post_error_message = "Form nonce was incorrect";
    } // } else { // if( wp_verify_nonce($_POST['yydev_portfolio_nonce_remove'], 'yydev_portfolio_action_remove') ) {

} // if( isset($_POST['yydev_portfolio_nonce_remove']) ) {


?>

<div class="wrap yydev_box_main">
    <h2>Wordpress Boxes</h2>
    
    <?php yydev_portfolio_echo_boxes_message_if_exists(); ?>
    <?php yydev_portfolio_echo_success_message_if_exists($success_message); ?>
    <?php yydev_portfolio_echo_error_message_if_exists($post_error_message); ?>
    
    <div class="insert-new-box">
        
        <h5>Add Boxes</h5>
        <form class="insert-box" method="POST" action="" style="direction: ltr;" >
            <label for="yydev_box_name">New Box Name</label>
            <input type="text" id="yydev_box_name" class="yydev_box_name" name="yydev_box_name" value="" />
        
            <label for="yydev_parent_page_id">Box Parent Page ID</label>
            <input type="text" id="yydev_parent_page_id " class="yydev_parent_page_id shorter_input" name="yydev_parent_page_id" value="" />
        
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
                <option value="<?php echo $box_type_value; ?>"><?php echo $box_type_name; ?></option>
<?php
        } // foreach($box_type_output as $box_type_select) {
        
    } // f(isset($box_type_output)) {
?>
            </select>
            
            <?php
                // creating nonce to make sure the form was submitted correctly from the right page
                wp_nonce_field( 'yydev_portfolio_action_main', 'yydev_portfolio_nonce_main' ); 
            ?>

            <input type="submit" name="submit-new-box" class="button-cursor" value="Submit Box" />
            <?php yydev_portfolio_show_error_message($box_name_error, '1'); ?>
        </form>
    
    </div><!--insert-new-box-->
            
            
    <table class="wp-list-table widefat fixed striped posts">
    <thead>
        <tr>
            <th style="width:80px;">ID</th>
            <th style="width:250px;">Box Name</th>
            <th style="width:150px;">Box Type</th>
            <th style="width:120px;text-align:center;">No. of Boxes</th>
            <th>Shortcode</th>
            <th style="width:190px;">Action</th>
        </tr>
    </thead>
    
    <tbody id="the-list">
    
       
    
<?php
    
// ================================================
// Echoing all the boxes from the database 
// ================================================
    
    global $wpdb;
    $wordpress_database_boxes = $wpdb->get_results("SELECT * FROM " . $table_name . " ORDER BY id DESC");
    
    // Echo there is no boxes if they are not in there
    if(empty($wordpress_database_boxes)) {
?>
    <tr class="no-items"><td class="colspanchange" colspan="6">No boxes found</td></tr>
<?php     
    } // if(empty($wordpress_database_boxes)) {
    
    
    
    foreach($wordpress_database_boxes as $yydev_portfolioes) {
        
    $page_url = esc_url( menu_page_url( 'yydev_portfolio', false ) );        
    
?>
        <tr>
            <td><a href="<?php echo $page_url . "&view=box&id=" . $yydev_portfolioes->id; ?>"><?php echo $yydev_portfolioes->id; ?></a></td>
            <td><a href="<?php echo $page_url . "&view=box&id=" . $yydev_portfolioes->id; ?>"><?php echo $yydev_portfolioes->name; ?></a></td>
            <td><a href="<?php echo $page_url . "&view=box&id=" . $yydev_portfolioes->id; ?>"><?php echo $yydev_portfolioes->box_type; ?></a></td>
            <td style="text-align:center;"><?php echo $wpdb->query("SELECT * FROM " . $box_table_name . " WHERE box_id = " . $yydev_portfolioes->id ); ?></td>
            <td><input class="output-code" type="text" value='[wordpress-boxes id="<?php echo $yydev_portfolioes->id;?>" name="<?php echo $yydev_portfolioes->slug;?>" limit="0"]' /></td>
            <td><a href="<?php echo $page_url . "&view=box&id=" . $yydev_portfolioes->id; ?>">Edit Box</a> &nbsp;&nbsp;&nbsp; / &nbsp;&nbsp;&nbsp;
                <form class="insert-form remove-data-form" method="POST" action="">
                    <?php wp_nonce_field( 'yydev_portfolio_action_remove', 'yydev_portfolio_nonce_remove' ); ?>
                    <input type="hidden" name="remove_portfolio_id" value="<?php echo $yydev_portfolioes->id; ?>" />
                    <input type="submit" class="remove-submit-button" name="submit_new_form" value="Delete Box" />
                </form>
            </td>
        </tr>
        
<?php
    } // foreach($wordpress_database_boxes as $yydev_portfolioes) {
    
?>

    </tbody>
    
    <tfoot>
        <tr>
            <th style="width:80px;">ID</th>
            <th style="width:250px;">Box Name</th>
            <th style="width:150px;">Box Type</th>
            <th style="width:120px;text-align:center;">No. of Boxes</th>
            <th>Shortcode</th>
            <th style="width:190px;">Action</th>
        </tr>
    </tfoot>
    
    </table>
        
<br />
<span id="footer-thankyou-code">This plugin was create by <a target="_blank" href="https://www.yydevelopment.com">YYDevelopment</a>. If you liked the plugin please give it a <a target="_blank" href="https://wordpress.org/plugins/yydevelopment-portfolio-boxes/#reviews">5 stars review</a>. 
If you want to help support this FREE plugin <a target="_blank" href="https://www.yydevelopment.com/coffee-break/?plugin=yydevelopment-portfolio-boxes">buy us a coffee</a>.</span>
</span>
<br />

</div><!--wrap yydev_box_main-->