<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php

    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php'); // Require to use dbDelta
    include('settings.php'); // Load the files to get the databse info    

    // Creating the first database table
    $yydev_portfolio_table_name = $yydev_portfolio_table_name;
    
    if( $wpdb->get_var("SHOW TABLES LIKE '{$yydev_portfolio_table_name}' ") != $yydev_portfolio_table_name ) {
        // The table we want to create doesn't exists
       
        $sql = "CREATE TABLE " . $yydev_portfolio_table_name . "( 
        id INTEGER(11) UNSIGNED AUTO_INCREMENT,
        parent_page_id INTEGER (11),
        items_per_page INTEGER (11),
        name VARCHAR (500),
        slug VARCHAR (500),
        container_width VARCHAR (500),
        box_width VARCHAR (500),
        box_height VARCHAR (500),
        button_text VARCHAR (500),
        box_type VARCHAR (500),
        order_by TINYINT(1),
        hidden_load TINYINT(1),
        allow_class TINYINT(1),
        PRIMARY KEY (id) 
        ) $charset_collate;";
        
        dbDelta($sql);
        
       
    }  // if( $wpdb->get_var("SHOW TABLES LIKE '{$yydev_portfolio_table_name}' ") != $yydev_portfolio_table_name ) {
     

    // Creating the second database table
    $yydev_secondary_table_name = $yydev_secondary_table_name;
    
    if( $wpdb->get_var("SHOW TABLES LIKE '{$yydev_secondary_table_name}' ") != $yydev_secondary_table_name ) {
        // The table we want to create doesn't exists
       
        $sql = "CREATE TABLE " . $yydev_secondary_table_name . "( 
        id INTEGER(11) UNSIGNED AUTO_INCREMENT,
        box_id INTEGER (11),
        position FLOAT,
        image_path TEXT,
        image_alt TEXT,
        url_link TEXT,
        title TEXT,
        description TEXT,
        open_new_tab TINYINT(1),
        nofollow_link TINYINT(1),
        box_width VARCHAR (500),
        box_height VARCHAR (500),
        button_text VARCHAR (500),
        image_class VARCHAR (500),
        PRIMARY KEY (id) 
        ) $charset_collate;";
        
        dbDelta($sql);
        
       
    }  // if( $wpdb->get_var("SHOW TABLES LIKE '{$yydev_secondary_table_name}' ") != $yydev_secondary_table_name ) {


// if the plugin change version and require to add database fields
if( isset($yydev_portfolioes_database_update ) ) {

    // ============================================================
    // Dealing with the main database table
    // ============================================================

    // creating an array with all the columns from the database
    $existing_columns = $wpdb->get_col("DESC {$yydev_portfolio_table_name}", 0);

    if($existing_columns) {

            // -------------------------------------------------------------
            // update the database for plugin version 1.2.0 - adding no follow links
            // -------------------------------------------------------------

            $new_db_column = 'allow_class';
            if( !in_array($new_db_column, $existing_columns) ) {
                // create the date column on the database
                $wpdb->query("ALTER TABLE $yydev_portfolio_table_name ADD $new_db_column TINYINT (1) NOT NULL");
            } // if( in_array($new_db_column, $existing_columns) ) {

    } // if($existing_columns) {

    // ============================================================
    // Dealing with the secondary database table
    // ============================================================

    // creating an array with all the columns from the database
    $existing_columns = $wpdb->get_col("DESC {$yydev_secondary_table_name}", 0);

    if($existing_columns) {

            // -------------------------------------------------------------
            // update the database for plugin version 1.2.0 - added class to db
            // -------------------------------------------------------------

            $new_db_column = 'nofollow_link';
            if( !in_array($new_db_column, $existing_columns) ) {
                // create the date column on the database
                $wpdb->query("ALTER TABLE $yydev_secondary_table_name ADD $new_db_column TINYINT (1) NOT NULL");
            } // if( in_array($new_db_column, $existing_columns) ) {

    } // if($existing_columns) {

} // if( isset($yydev_portfolioes_database_update ) ) {