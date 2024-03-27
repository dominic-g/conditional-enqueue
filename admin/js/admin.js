jQuery(document).ready(function($) {
    $('select.conditional_enqueue_settings').change(function() {
        var pageId = $(this).val();
        var assets = conditionalEnqueue.assets;
        var checkboxesHtml = '';

        // Generate checkboxes for each asset
        assets.forEach(function(asset) {
            checkboxesHtml += '<input type="checkbox" name="conditional_enqueue_settings[assets][pageId]" value="' + asset.handle + '"> ' + asset.handle + '<br>';
        });

        console.log(checkboxesHtml);

        $('#assets-section-placeholder').html(checkboxesHtml);
    });
});
// console.log(conditionalEnqueue.assets);
