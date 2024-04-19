jQuery(document).ready(function($) {
    
    $('select.conditional_enqueue_settings').change(function() {
        let cur = $(this);


        var nonce = cur.attr('data-key');

        // Data to send in the request
        var data = {
            action: 'request_assets_for_page',
            page: cur.val(),
            nonce: nonce
        };

        console.log(data);

        // Make the AJAX POST request
        $.post(conditionalEnqueue.ajaxUrl, data, function(response) {
            // Update the content of the specified div with the response
            $('#result').html(response);
        });

        console.log(cur, cur.val());
    });
});
// console.log(conditionalEnqueue.assets);
