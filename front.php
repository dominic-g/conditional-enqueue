<?php

function portfolio_scripts() {
    if (isset($_GET['conditional_enqueue_capture_assets']) && $_GET['conditional_enqueue_capture_assets'] === 'true') {

    	$slug = get_page_slug();
        global $wp_scripts, $wp_styles;

        $wp_scripts = new WP_Scripts();
        $wp_styles = new WP_Styles();

        $styles = array();
	    $scripts = array();

	    foreach( $wp_styles->queue as $style ) {
	        $styles[] = ['handle' => $wp_styles->registered[$style]->handle, 'src' => $wp_styles->registered[$style]->src];
	    }

	    foreach( $wp_scripts->queue as $script ) {
	        $scripts[] = ['handle' => $wp_scripts->registered[$script]->handle, 'src' => $wp_scripts->registered[$script]->src];
	    } 

        update_option('conditional_enqueued_assets_'.$slug, array(
            'scripts' => $scripts,
            'styles' => $styles,
        ));

        exit;
    }
}
add_action('wp_enqueue_scripts', 'portfolio_scripts', 1000000);


function get_page_slug(){
	$current_page_slug = 'ALL';

	if (is_singular()) {
	    $current_page_slug = 'SINGLE';
	    if (is_page()) {
		    global $post;
	        $current_page_slug = $post->ID;
	    }
	} elseif (is_archive()) {
	    $current_page_slug = 'ARCHIVE';
	    if (is_category()) {
	        $current_page_slug = 'CAT';
	    } elseif (is_tag()) {
	        $current_page_slug = 'TAG';
	    } elseif (is_author()) {
	        $current_page_slug = 'AUTHOR';
	    } elseif (is_date()) {
	        $current_page_slug = 'DATE';
	    }
	} elseif (is_home()) {
	    $current_page_slug = 'HOME';
	    if (is_front_page()) {
	        $current_page_slug = 'FRONT';
	    }
	} elseif (is_search()) {
	    $current_page_slug = 'SEARCH';
	} elseif (is_404()) {
	    $current_page_slug = '404';
	}

	return $current_page_slug;
}
