<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<script>
    
jQuery("document").ready(function($) {
    
    // ==================================================
    // Confirm before removing a boxes on the box page
    // ==================================================
       
    $(".yydev-remove-box").click(function() {
        if (confirm("Are you sure you want to permanently remove this box and all the boxes inside it?"))
            return true;
        else
            return false;
    }) ;

    // ==================================================
    // Dealing with the editing image portfolio box section
    // ==================================================

    $('.edit-button-image-upload').click(function(e) {
        
        var inputIMGvalueChange = "." + $(this).attr('id'); // Getting the id that and it's the same as the id tag for the text input for the image path
        var changeImagePath = "img." + $(this).attr('id'); // changing the page of the image so it will update the new image instead

        e.preventDefault();
        var image = wp.media({
        title: 'Upload Image'}).open()
        .on('select', function(e){

            // This will return the selected image from the Media Uploader, the result is an object
            var images_length = image.state().get("selection").length;
            var images = image.state().get("selection").models;

            console.log(images);

            var image_url = images[0].toJSON().url;
            var image_alt = images[0].toJSON().alt;
            var image_caption = images[0].toJSON().caption;
            var image_title = images[0].toJSON().title;
            var image_id = images[0].toJSON().id;

            // Let's assign the url value to the input field
            $( changeImagePath ).attr('src', image_url);
            $( inputIMGvalueChange ).val(image_url);

            // changing alt value only if the user pick image on the main section
            if(inputIMGvalueChange == ".image_url") {
                $('#yydev_box_alt').val(image_alt);
                $('#yydev_image_id').val(image_id);
            } // if(inputIMGvalueChange == ".image_url") {

        }); // .on('select', function(e){

    }); // $('.edit-button-image-upload').click(function(e) {

    // ==================================================
    // Dealing with the editing image portfolio box section
    // ==================================================

    $(document).on('click', '.add-button-image-upload', function (e) {
        
        var inputIMGvalueChange = "." + $(this).attr('id'); // Getting the id that and it's the same as the id tag for the text input for the image path
        var changeImagePath = "img." + $(this).attr('id'); // changing the page of the image so it will update the new image instead

        e.preventDefault();
        var image = wp.media({
        title: 'Upload Image',
        // mutiple: true if you want to upload multiple files at once
        multiple: true}).open().on('select', function(e){

            // This will return the selected image from the Media Uploader, the result is an object
            var images_length = image.state().get("selection").length;
            var images = image.state().get("selection").models;

            console.log(images);

            // if user selected more than one image
            if( images_length > 1 ) {

                // ----------------------------------------------
                // creating/clone more image elements we can edit
                // ----------------------------------------------
                var tableClassName;
                for(var iii = 1; iii < images_length; iii++) {
                    $(".add_boxes_table").clone().insertAfter("table.add_boxes_table:last");

                    tableClassName = 'add_boxes_table' + iii;
                    $("table.add_boxes_table:last").attr('class', tableClassName);
                    $( "." + tableClassName + " img.add-button-image-upload").attr('id', tableClassName);

                    $( "." + tableClassName + " .yydev_open_new_tab").attr('id', tableClassName + "label");
                    $( "." + tableClassName + " .yydev_open_new_tab_label").attr('for', tableClassName + "label");

                } // for(var iii = 1; iii < images_length; iii++) {

                // ----------------------------------------------
                // adding the data to the boxes
                // ----------------------------------------------
                for(var iii = 0; iii < images_length; iii++) {

                    image_url = images[iii].toJSON().url;
                    image_alt = images[iii].toJSON().alt;
                    image_caption = images[iii].toJSON().caption;
                    image_title = images[iii].toJSON().title;
                    image_id = images[iii].toJSON().id;

                    // define the div table class that we output the text into
                    tableClassName = '.add_boxes_table';
                    if( iii > 0 ) {
                        tableClassName = '.add_boxes_table' + iii;
                    } // if( iii > 0 ) {

                    // Let's assign the url value to the input field
                    $( tableClassName +  " .img_url_button" ).attr('src', image_url);
                    $( tableClassName +  " input.image_url" ).val(image_url);
                    $( tableClassName +  " #yydev_box_alt" ).val(image_alt);
                    $( tableClassName + ' #yydev_image_id').val(image_id);
                    
                } // for(var iii = 0; iii < images_length; iii++) {

            } else { // if( images_length > 1 ) {

                // if user seleceted only one image he wanted to change
                var image_url = images[0].toJSON().url;
                var image_alt = images[0].toJSON().alt;
                var image_caption = images[0].toJSON().caption;
                var image_title = images[0].toJSON().title;
                var image_id = images[0].toJSON().id;

                // Let's assign the url value to the input field
                $( inputIMGvalueChange +  " .img_url_button" ).attr('src', image_url);
                $( inputIMGvalueChange +  " input.image_url" ).val(image_url);
                $( inputIMGvalueChange +  " #yydev_box_alt" ).val(image_alt);
                $( inputIMGvalueChange + ' #yydev_image_id').val(image_id);

            } // } else { // if( images_length > 1 ) {

        }); // multiple: true}).open().on('select', function(e){

    }); // $(document).on('click', '.add-button-image-upload', function () {

}); // jQuery("document").ready(function($) {

</script>