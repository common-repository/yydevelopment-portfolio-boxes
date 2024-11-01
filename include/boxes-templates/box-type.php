<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php

$yydev_box_type = [];

// ===============================================================
// Portoflio Box
// ===============================================================

$yydev_box_type[] = array(
    'box_type_value' => 'potrfolio-boxes', // output the box select value & this value have to be the same value as the folder name for the box
    'box_type_name' => 'Portfolio Box', // The out name for the select option on the plugin admin page
);

// ===============================================================
// HTML5 Banners
// ===============================================================

$yydev_box_type[] = array(
    'box_type_value' => 'html5-banners', // output the box select value & this value have to be the same value as the folder name for the box
    'box_type_name' => 'HTML5 Banners', // The out name for the select option on the plugin admin page
);


// ===============================================================
// This function check if each of the folder for each box exists
// if the box_type_value match the name of the folder then the file
// exists and it will be output to the page.
// If the folder name does not mathc or the folder not exists the array
// won't be output to the page
// ===============================================================

foreach($yydev_box_type as $yydev_exists_boxes) {
    // Getting the folder box type path
    $exists_box_path = dirname( __FILE__ ) . "/" . $yydev_exists_boxes['box_type_value'] . "";
    
    if(file_exists($exists_box_path)) {
        $box_type_output[] = $yydev_exists_boxes;
    } // if(file_exists($exists_box_path)) {
    
} // foreach($yydev_box_type as $yydev_exists_boxes) {

?>