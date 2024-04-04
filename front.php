<?php

function conditional_enqueue_scripts_current_page() {
    if (isset($_GET['conditional_enqueue_capture_assets']) && $_GET['conditional_enqueue_capture_assets'] === 'set') {

    	$slug = get_page_slug();
        global $wp_scripts, $wp_styles;


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
        print_r(
            json_encode(
                ['conditional_enqueued_assets_'.$slug => array(
                    'scripts' => $scripts,
                    'styles' => $styles,
                )]
            )
        );

        exit;
    }
}
add_action('wp_enqueue_scripts', 'conditional_enqueue_scripts_current_page', 1000000);

/*if (isset($_GET['conditional_enqueue_capture_assets'])) {
    // Start output buffering
    ob_start();

    // Trigger the wp_enqueue_scripts action
    do_action('wp_enqueue_scripts');

    // Get the captured output
    $output = ob_get_clean();

    // Find the position of the JSON string
    $jsonStart = strpos($output, '{"conditional_enqueued_assets_FRONT"');

    // Extract the JSON part
    $jsonString = substr($output, $jsonStart);

    // Set the content type header
    header('Content-Type: application/json');

    // Output the JSON string
    echo $jsonString;

    // Exit to prevent further output
    exit;
}*/


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

function get_url_from_slug($slug) {
    $url = null;

    if ($slug == 'SINGLE') {
        $args = array(
            'posts_per_page' => 1,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC'
        );
        $first_post = get_posts($args);

        if (!empty($first_post)) {
            $url = get_permalink($first_post[0]->ID);
        }
    } elseif ($slug == 'ARCHIVE') {
        $url = get_post_type_archive_link('post');
    } elseif ($slug == 'CAT') {
        $url = get_category_link(get_query_var('cat'));
    } elseif ($slug == 'TAG') {
        $url = get_tag_link(get_query_var('tag_id'));
    } elseif ($slug == 'AUTHOR') {
        $authors = get_users(array('number' => 1));
        if (!empty($authors)) {
            $url = get_author_posts_url($authors[0]->ID);
        }
    } elseif ($slug == 'DATE') {
        $url = get_day_link(get_query_var('year'), get_query_var('monthnum'), get_query_var('day'));
    } elseif ($slug == 'HOME') {
        $url = home_url('/');
    } elseif ($slug == 'FRONT') {
        $url = home_url();
    } elseif ($slug == 'SEARCH') {
        $url = home_url('/?s=' . urlencode(get_search_query()));
    } elseif ($slug == '404') {
        $url = home_url('/404');
    }  elseif (is_numeric($slug)) {
        $page_exists = get_post($slug);

        if ($page_exists && $page_exists->post_type == 'page') {
            $url = get_permalink($slug);
        }else {
            $url = home_url('/');
        }
    } else {
        $url = home_url('/');
    }

    return $url;
}
