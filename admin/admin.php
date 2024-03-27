<?php

defined('ABSPATH') or die("Cannot access pages directly.");

add_action('admin_menu', 'conditional_enqueue_settings_menu');
add_action('admin_init', 'conditional_enqueue_settings_init');

/**
 * Adds the settings page to the admin menu.
 */
function conditional_enqueue_settings_menu() {
    add_options_page(
        'Conditional Enqueue Settings',
        'Conditional Enqueue',
        'manage_options',
        'conditional-enqueue-settings',
        'conditional_enqueue_settings_page'
    );
}

/**
 * Initializes settings.
 */
function conditional_enqueue_settings_init() {
    register_setting('conditional-enqueue-settings-group', 'conditional_enqueue_settings');

    add_settings_section(
        'conditional_enqueue_settings_section',
        'Select a Page and Disable Assets',
        null,
        'conditional-enqueue-settings'
    );

    add_settings_field(
        'page_select',
        'Select a Page',
        'conditional_enqueue_settings_page_select',
        'conditional-enqueue-settings',
        'conditional_enqueue_settings_section'
    );

    // Placeholder for assets section
    add_settings_field(
        'assets_section',
        'Disable Assets',
        'conditional_enqueue_settings_assets_section',
        'conditional-enqueue-settings',
        'conditional_enqueue_settings_section'
    );
}
function conditional_enqueue_settings_assets_section() {
    echo '<div id="assets-section-placeholder"></div>';
}


function conditional_enqueue_scripts() {
    wp_enqueue_script('conditional-enqueue-admin', plugin_dir_url(__FILE__) . 'js/admin.js', array('jquery'), '1.0', true);

    wp_localize_script('conditional-enqueue-admin', 'conditionalEnqueue', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'assets' => retrieve_assets(),
    ));
}
add_action('admin_enqueue_scripts', 'conditional_enqueue_scripts');

/**
 * Renders the settings page.
 */
function conditional_enqueue_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('Conditional Enqueue Settings', 'conditional-enqueue'); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('conditional-enqueue-settings-group');
            do_settings_sections('conditional-enqueue-settings');

            conditional_enqueue_settings_assets_section();
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

/**
 * Renders the page selection dropdown.
 */
function conditional_enqueue_settings_page_select() {
    // $pages = get_pages();
    $pages = retrive_all_pages();
    if(count($pages) > 0){
        $options = get_option('conditional_enqueue_settings');
        echo '<select name="conditional_enqueue_settings[page_select]" class="conditional_enqueue_settings">';
        foreach ($pages as $key => $page) {
            $selected = (isset($options['page_select']) && is_object($page) && property_exists($page, 'ID') && $options['page_select'] == $page->ID) ? 'selected' : '';
            echo "<option value='{$key}' {$selected}>{$key}</option>";
        }
        echo '</select>';

        print_r(retrieve_assets());
    }
}

function retrieve_assets(){
     global $wp_scripts, $wp_styles;

    $styles = array();
    $scripts = array();

    // print_r($wp_scripts);
    print_r(wp_scripts());


    // Get enqueued styles
    foreach( $wp_styles->queue as $style ) {
        $styles[] = ['handle' => $wp_styles->registered[$style]->handle, 'src' => $wp_styles->registered[$style]->src];
    }

    // Get enqueued scripts
    foreach( $wp_scripts->queue as $script ) {
        $scripts[] = ['handle' => $wp_scripts->registered[$script]->handle, 'src' => $wp_scripts->registered[$script]->src];
    } 

    return array(
        'styles' => $styles,
        'scripts' => $scripts
    );
}


/**
 * Retive pages
 */
function retrive_all_pages(){
    // Retrieve Pages
    $pages = get_pages();
    $pages_array = array();

    foreach ($pages as $page) {
        if(is_object($page) && property_exists($page, 'ID') && is_numeric($page->ID)){
            $pages_array[$page->post_title] = $page->ID;
        }
    }

    // Combine all results into a single array
    $combined_pages = array(
        'Home Page' => 'IS_FRONT',
        'Blog Post Index' => 'IS_HOME',
        'Archive Pages' => 'IS_ARCHIVE',
        'Category Archive Page' => 'IS_CAT',
        'Tag Archive Page' => 'IS_TAG',
        'Author Archive Page' => 'IS_AUTHOR',
        'Date Archive Page' => 'IS_DATE',
        'Search Page' => 'IS_SEARCH',
        'Not Found (404) Page' => 'IS_404',
        'Single Post' => 'IS_SINGLE'
        
    );

    $combined_pages = array_merge($combined_pages, $pages_array);
    return $combined_pages;
}