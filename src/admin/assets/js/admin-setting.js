(function ($) {

    // =================== Store Settings =================== //
    $(document).on("click", "#lwpc-setting-store-save", function (e) {
        e.preventDefault();
        $(this).addClass('loading');
        const that = this;

        $.post(lwpc_admin.ajax_url, {
            action: 'lwpc_store_settings_save',
            settings: $("#settings form").serialize(),
            security: lwpc_admin.ajax_nonce,
        }, function (response) {
            if (response.trim() === 'action_success') {
                $(that).removeClass('loading');
            } else {
                location.reload();
            }
        }).fail(function () {
            alert('Please check your internet connection');
        });
    });

    // =================== Shipping Package Status =================== //
    $(document).on("change", ".lwpc_shipping_package_status", function (e) {
        $(this).addClass('loading');
        const packageId = $(this).attr('data-action');
        const that = $(this);

        $.post(lwpc_admin.ajax_url, {
            action: 'lwpc_shipping_package_status',
            status: that.val(),
            package_id: packageId,
            security: lwpc_admin.ajax_nonce,
        }, function (response) {
            console.log(response)
            // if (!response) alert('action failed');
            that.removeClass('loading')
        }).fail(function () {
            alert('Please check your internet connection');
        });
    });

    // =================== Shipping Settings =================== //
    $(document).on("click", "#lwpc-setting-shipping-save", function (e) {
        e.preventDefault();
        $(this).addClass('loading');
        const that = this;

        $.post(lwpc_admin.ajax_url, {
            action: 'lwpc_shipping_settings_save',
            settings: $("#settings form").serialize(),
            security: lwpc_admin.ajax_nonce,
        }, function (response) {
            if (response.trim() === 'action_success') {
                $(that).removeClass('loading');
            } else {
                location.reload();
            }
        }).fail(function () {
            alert('Please check your internet connection');
        });
    });


    /**
     * ⚡ Show Shipping Manager 
     * Dsiplaying Shipping Manager
     * 
     * @scope Global
     * @since 0.5.0
     */
    $(document).on("click", ".lwpc-shipping-manager", function (e) {
        
        let shippingEditor = $("#lwpc-shipping-manager-editor");
        // On Loading
        // shippingEditor.html(shimmer);

        // On Completed
        let thisID = $(this).attr('id');

        // // AJAX Request
        // $.post(lokuswp_admin.ajax_url, {
        //     action: 'lokuswp_admin_shipping_manage',
        //     id: thisID,
        //     security: lokuswp_admin.ajax_nonce,
        // }, function (response) {

        //     let html = response;

        //     // Manipulate InnerHTML
        //     var $html = $('<div />', {
        //         html: html
        //     });

        //     $html.find('form').attr("id", thisID + '_form'); // Change ID
        //     shippingEditor.html($html.html());

        //     //  $(".selectlive").select2({
        //     //      allowClear: true,
        //     //      width: '100%',
        //     //  });

        shippingEditor.find('.shipping-editor').removeClass('d-hide');

        // }).fail(function () {
        //     alert('Please check your internet connection');
        // });

        shippingEditor.parent().show();
        shippingEditor.parent().css('z-index', '9999');
    });

    /**
     * ⚡ Close Payment Method Manager Panel
     * Dsiplaying Payment Method Manager
     * 
     * @scope Global
     * @since 0.5.0
     */
    $(document).on("click", "#lwpc-shipping-manager-editor .panel-close", function (e) {

        let shippingEditor = $("#lwpc-shipping-manager-editor");

        shippingEditor.parent().hide();
        shippingEditor.parent().css('z-index', '0');
        shippingEditor.html('');

        //  $(".selectlive").select2('destroy');
    });

})(jQuery)