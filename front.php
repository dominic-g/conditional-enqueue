<?php

function conditional_enqueue_scripts_current_page() {
    if (isset($_GET['conditional_enqueue_capture_assets']) && $_GET['conditional_enqueue_capture_assets'] === 'true') {

    	$slug = get_page_slug();
        global $wp_scripts, $wp_styles;

        // $wp_scripts = new WP_Scripts();
        // $wp_styles = new WP_Styles();
        // print_r($wp_scripts);
        // print_r($wp_styles);

        $styles = array();
	    $scripts = array();

	    foreach( $wp_styles->queue as $style ) {
	        $styles[] = ['handle' => $wp_styles->registered[$style]->handle, 'src' => $wp_styles->registered[$style]->src];
	    }

	    foreach( $wp_scripts->queue as $script ) {
	        $scripts[] = ['handle' => $wp_scripts->registered[$script]->handle, 'src' => $wp_scripts->registered[$script]->src];
	    } 
	    print_r(
	    	array('conditional_enqueued_assets_'.$slug => array(
            'scripts' => $scripts,
            'styles' => $styles,
        ))
	    );

        update_option('conditional_enqueued_assets_'.$slug, array(
            'scripts' => $scripts,
            'styles' => $styles,
        ));

        exit;
    }
}
add_action('wp_enqueue_scripts', 'conditional_enqueue_scripts_current_page', 1000000);


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

    switch ($slug) {
        case 'SINGLE':
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
            break;
        case 'ARCHIVE':
            $url = get_post_type_archive_link('post');
            break;
        case 'CAT':
            $url = get_category_link(get_query_var('cat'));
            break;
        case 'TAG':
            $url = get_tag_link(get_query_var('tag_id'));
            break;
        case 'AUTHOR':
            $authors = get_users(array('number' => 1));
            if (!empty($authors)) {
                $url = get_author_posts_url($authors[0]->ID);
            }
            break;
        case 'DATE':
            $url = get_day_link(get_query_var('year'), get_query_var('monthnum'), get_query_var('day'));
            break;
        case 'HOME':
            $url = home_url('/');
            break;
        case 'FRONT':
            $url = home_url();
            break;
        case 'SEARCH':
            $url = home_url('/?s=' . urlencode(get_search_query()));
            break;
        case '404':
            $url = home_url('/404');
            break;
        default:
        	$url = home_url('/');
            break;
    }

    return $url;
}
