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

    // Add more settings fields for CSS and JS files here
}

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
        // print_r($pages); return;
        echo '<select name="conditional_enqueue_settings[page_select]">';
        foreach ($pages as $key => $page) {
            $selected = (isset($options['page_select']) && is_object($page) && property_exists($page, 'ID') && $options['page_select'] == $page->ID) ? 'selected' : '';
            echo "<option value='{$key}' {$selected}>{$key}</option>";
        }
        echo '</select>';
    }
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