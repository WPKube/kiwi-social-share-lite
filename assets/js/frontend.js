jQuery(document).ready(function (e) {
    try {
        kiwi.frontend.highlightShare(e);
        kiwi.frontend.modalPopup(e);
        kiwi.frontend.handleFloatingBarDisplay(e);
    } catch (err) {
        console.warn(err);
    }

    var shared_count = jQuery('.kiwi-share-count');
    console.log(shared_count);
    if (shared_count.attr('no-transient') == "true") {
        jQuery.ajax({
            url: '/wp-admin/admin-ajax.php',
            type: "POST",
            data: {
                'action': 'get_shared_count'
            },
            success: function (data) {
                console.log(data);
            },
            dataType:"json"
        });
    }
});
