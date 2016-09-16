jQuery(document).ready(function( $ ){

    // Add to Lookbook
    if($('.gpp-lookbook-add').length) {
        $( document ).on('click','.gpp-lookbook-add', function() {
            if ($(this).hasClass('saved-to-lookbook')) {
                return false;
            } else {
                var id = $(this).attr('id');
                id = id.split('lookbook-');
                $(this).attr('disabled', true);
                $('.gpp-lookbook-text', this).text( gpp_lookbook.saving );
                $.ajax({
                    url: gpp_lookbook.ajaxurl,
                    type: "POST",
                    dataType: 'json',
                    data: { action : 'gpp_lookbook_add_to', id : id[1] },
                    success:function(data) {
                        if(true==data.success) {

                            $('#lookbook-'+data.postID + ' .gpp-lookbook-text').text( gpp_lookbook.saved );
                            $('#lookbook-'+data.postID).prev().addClass('lookbook-active');

                            $('#lookbook-'+data.postID).closest('.lookbook').find('.genericon:first').removeClass('genericon-category');
                            $('#lookbook-'+data.postID).closest('.lookbook').find('.genericon:first').addClass('genericon-checkmark');

                            var count = $('.lookbook-menu .lookbook-counter').html();
                            count = parseInt(count) + 1;
                            $('.lookbook-menu .lookbook-counter').html(count);
                            $('#lookbook-'+data.postID).removeAttr("disabled");
                            $('#lookbook-'+data.postID).addClass("saved-to-lookbook");
                        }
                    }
                });
                return false;
            }
        });
    }

    // Remove from Lookbook
    if($('.gpp-lookbook-remove').length) {
        $( document ).on('click','.gpp-lookbook-remove', function() {
            var id = $(this).attr('id');
            id = id.split('lookbook-');
            $.ajax({
                url: gpp_lookbook.ajaxurl,
                type: "POST",
                dataType: 'json',
                data: { action : 'gpp_lookbook_remove_from', id : id[1] },
                success:function(data) {
                    if(true==data.success) {
                        $('#lookbook-'+data.postID).parent().remove();
                        var count = $('.lookbook-menu .lookbook-counter').html();
                        count = parseInt(count) - 1;
                        $('.lookbook-menu .lookbook-counter').html(count);
                        location.reload();
                    }
                }
            });
            return false;
        });
    }

    function gpp_lookbook_is_email( email ) {
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        if ( ! emailReg.test( email ) ) {
            return false;
        } else {
            return true;
        }
    }

    $('#gpp_lookbook_name_field, #gpp_lookbook_email_field').on('keyup', function(){

        var email = $('#gpp_lookbook_email_field').val().trim();
        var name = $('#gpp_lookbook_name_field').val().trim();

        if ( gpp_lookbook_is_email( email ) && email.length && name.length ){
            $('#gpp_lookbook_submit').removeAttr('disabled');
        } else {
            $('#gpp_lookbook_submit').attr('disabled', true);
        }
    });


    $('#gpp_lookbook_form').on('submit', function( e ){
        e.preventDefault();
        $.ajax({
            url: gpp_lookbook.ajaxurl,
            type: "POST",
            dataType: 'json',
            data: {
                action: 'gpp_lookbook_form_handler',
                security: $('#gpp_lookbook_nonce').val(),
                name: $('#gpp_lookbook_name_field').val(),
                email: $('#gpp_lookbook_email_field').val(),
                badger: $('#gpp_lookbook_badger').val()
            },
            success: function( msg ){
                if ( msg.status == 'success' ){
                    $('#gpp_lookbook_submit').val( gpp_lookbook.success );
                    window.location.replace( msg.download_url );
                }
            }
        });
    });


    $('.gpp-lookbook-remove-all').on('click',function( e ) {
        e.preventDefault();
        $this = $(this);
        $.ajax({
            url: gpp_lookbook.ajaxurl,
            type: "POST",
            dataType: 'json',
            data: {
                action: 'gpp_lookbook_remove_all',
                security: $this.attr('data-gpp-lookbook-nonce')
            },
            success: function(msg) {
                if ( true == msg.success ) {
                    count = 0;
                    $('.lookbook-menu .lookbook-counter').html(count);
                    location.reload();
                }
            }
        });
        return false;
    });


    $('.gpp-lookbook .gpp-lookbook-grid').hover(function(){
        $(this).find('img').css({'opacity':'0.3'});
        $(this).find('.gpp-lookbook-remove').stop().css({'opacity':'1', 'display':'block'});
    },function(){
        $(this).find('img').css({'opacity':'1'});
        $(this).find('.gpp-lookbook-remove').stop().css('opacity','0');
    });

});