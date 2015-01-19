jQuery(document).ready(function() {

    // Select all checkboxes
    jQuery('.selectall').click(function() {
        jQuery(this).closest('form').find(':checkbox').attr('checked', this.checked);
    });
    
    
    // Initialise prettyPhoto
    // http://www.no-margin-for-errors.com/projects/prettyphoto-jquery-lightbox-clone/documentation
    jQuery("a[rel^='lightbox']").prettyPhoto({
        theme: 'light_square',
        slideshow: false,
        deeplinking: false,
        social_tools: null
    });


    // AJAX user validation
    jQuery('#yt_username').keyup(function() {

        var inputElement    = jQuery(this).closest('td').find('input');
        var descElement     = jQuery(this).closest('td').find('.description');
        var msgElement      = jQuery(this).closest('td').find('span#mycp-message');
        var username        = jQuery(this).val();
        var url             = msdObject.pluginsUrl + "/ajax.php";
        
        var msgOk       = msdObject.userOk;
        var msgError_00 = msdObject.userError_00;
        var msgError_01 = msdObject.userError_01;
        var msgError_02 = msdObject.userError_02;


        jQuery.get(url, {action: 'yt_user_verification', user: username})
                .done(function(data) {
            if (data === '0') {
                //alert(descVal);
                jQuery(msgElement)
                        .removeClass('error')
                        .text(msgOk)
                        .show();
                
                jQuery(inputElement)
                        .removeClass('error');
            } else if(data === '1') {
                jQuery(msgElement)
                        .addClass('error')
                        .text(msgError_01)
                        .show();
                jQuery(inputElement).addClass('error');
                //jQuery(inputElement).after('<span id="" class="description error mycp-error-message">Error: User does not exist!</span>');
            } else if(data === '2') {
                jQuery(msgElement)
                        .addClass('error')
                        .text(msgError_02)
                        .show();
                jQuery(inputElement).addClass('error');
                //jQuery(inputElement).after('<span id="" class="description error mycp-error-message">Error: User does not exist!</span>');
            }else {
                jQuery(msgElement)
                        .addClass('error')
                        .text(msgError_00)
                        .show();
                jQuery(inputElement).addClass('error');
                //jQuery(inputElement).after('<span id="" class="description error mycp-error-message">Error: User does not exist!</span>');
            }
        })
                .fail(function() {
            alert("error");
        });
    }); // /AJAX user validation
});

