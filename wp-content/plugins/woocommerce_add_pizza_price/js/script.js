jQuery('document').ready(function(){
    jQuery('[name="timer-expire"]').change( function(){
        if(jQuery('[name="timer-expire"]').val() == 'Message'){
            jQuery('[name="expire-msg"]').show();
        } else{
            jQuery('[name="expire-msg"]').hide();
        }
    });
    jQuery('#menu_type').change(function(){
        if(jQuery('#menu_type').val() == 'tray'){
            jQuery('._wc_min_qty_product_field').show();
        } else{
            jQuery('._wc_min_qty_product_field').hide();
        }
    })
    if(jQuery('#menu_type').val() == 'tray'){
        jQuery('._wc_min_qty_product_field').show();
    }
    jQuery('.main-course #_main_course_check').attr('checked','checked');

});