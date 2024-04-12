jQuery(document).ready(function($) {
    
    $('select.conditional_enqueue_settings').change(function() {
        let cur = $(this);

        console.log(cur, cur.val());
    });
});
// console.log(conditionalEnqueue.assets);
