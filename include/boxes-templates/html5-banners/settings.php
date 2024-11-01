<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php

// ====================================================
// Choosing which elements to display and which not to
// The settings below will allow hide or display fields
// on the page depending on the box type.
// if you give value 1 it will display the input
// if you give value 0 it will not display it
// ====================================================

$box_button_text = 0; // Allow to display "Boxes Button Text"
$box_upload_image = 1; // Allow to upload image for the boxes
$box_image_alt = 0; // Allow to add image alt tag
$box_description_text = 0; // Allow to display "Box Description" text 
$box_title_text = 0; // Allow to display the "Box Title" text 
$box_width_value = 1; // Allow to choose width for the boxes "Box Width" 
$box_height_value  = 1; // Allow to choose height for the boxes "Box height" 
$box_url_link = 0; // allow to choose link to the boxes

// ====================================================
// creating the box class name
// ====================================================

$main_box_class = "wordpress-image-title-box";

?>