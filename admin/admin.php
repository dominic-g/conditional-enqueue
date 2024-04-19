<?php

defined('ABSPATH') or die("Cannot access pages directly.");

require_once WP_PLUGIN_DIR . '/conditional-enqueue/admin/templates/settings.php';

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
        'conditional_enqueue_render_tabs'
        // 'conditional_enqueue_settings_page'
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

add_action('wp_ajax_request_assets_for_page', 'handle_request_assets');

function handle_request_assets(){

    print_r($_POST);
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'handle_request_assets')) {
        wp_send_json_error('Invalid nonce');
    }

    require_once( ABSPATH . WPINC . '/class-http.php' );

    $slug = isset($_POST['page'])?$_POST['page']:null;

    $url = get_url_from_slug($slug);
    $params = array(
        'conditional_enqueue_capture_assets' => 'set',
        'nonce' => wp_create_nonce('handle_request_assets_call_page'),
    );
    print_r(PHP_EOL.$url.PHP_EOL);
    $url = add_query_arg( $params, $url );

    $response = wp_remote_get( $url );

    $output = isset($response['body'])?$response['body']:null;

    $jsonStart = strpos($output, '{"conditional_enqueued_assets_'.$slug);

    // Extract the JSON part
    $jsonString = substr($output, $jsonStart);

    $data = json_decode($jsonString, true);
    print_r($data);

    // Check if the request was successful
    if ( !is_wp_error( $response ) && $response['response']['code'] == 200 ) {
        $body = wp_remote_retrieve_body( $response );
        // Process $body as needed
    } else {
        if ( is_wp_error( $response ) ) {
            $error_message = $response->get_error_message();
        } else {
            $http_code = $response['response']['code'];
        }
    }
}

function conditional_enqueue_scripts() {


    $current_screen = get_current_screen();
    if ($current_screen && $current_screen->id === 'settings_page_conditional-enqueue-settings') {
        wp_enqueue_script('conditional-enqueue-admin', plugin_dir_url(__FILE__) . 'js/admin.js', array('jquery'), filemtime(plugin_dir_path(__FILE__) . 'js/admin.js'), true);
        wp_enqueue_script('conditional-enqueue-select-search', plugin_dir_url(__FILE__) . 'js/select-search.js', [], filemtime(plugin_dir_path(__FILE__) . 'js/select-search.js'), true);

        wp_enqueue_style( 'conditional-enqueue-admin', plugin_dir_url(__FILE__) . 'css/admin.css', array(), filemtime(plugin_dir_path(__FILE__) . 'css/admin.css') );

        wp_localize_script('conditional-enqueue-admin', 'conditionalEnqueue', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'assets' => retrieve_assets(),
        ));
    }
}
add_action('admin_enqueue_scripts', 'conditional_enqueue_scripts');

/**
 * Renders the settings page.
 */
function conditional_enqueue_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Conditional Enqueue Settings', 'conditional-enqueue'); ?></h1>
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

    $pages = retrive_all_pages();
    if(count($pages) > 0){
    ?>
    <div class="form" id="form">
        <div class="form-group">
            <span class="form-arrow"><i class="bx bx-chevron-down">
                <svg width="24px" height="24px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <rect x="0" fill="none" width="24" height="24"/>
                    <g>
                    <path d="M7 10l5 5 5-5"/>
                    </g>
                </svg>
            </i></span>

            <select name="conditional_enqueue_settings[page_select]" class="conditional_enqueue_settings dropdown" data-key="<?php echo(esc_attr(wp_create_nonce('handle_request_assets'), 'conditional-enqueue')); ?>">
              
            <?php
                echo '<option disabled selected>Select Page</option>';
                foreach ($pages as $key => $page) {
                    $selected = (isset($options['page_select']) && is_object($page) && property_exists($page, 'ID') && $options['page_select'] == $page->ID) ? 'selected' : '';
                    echo "<option value='".esc_attr($page, 'conditional-enqueue')."' ".esc_html($selected, 'conditional-enqueue').">".esc_html($key, 'conditional-enqueue')."</option>";
                }
            ?>
            </select>
        </div>
    </div>
    <?php
    }else{
        esc_attr_e("Sorry No Pages have been found", 'conditional-enqueue');
    }
}

function retrieve_assets(){
     global $wp_scripts, $wp_styles;

    $styles = array();
    $scripts = array();

    // print_r($wp_scripts);
    // print_r(wp_scripts());


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
 * Retrive pages
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
        'Home Page' => 'FRONT',
        'Blog Post Index' => 'HOME',
        'Archive Pages' => 'ARCHIVE',
        'Category Archive Page' => 'CAT',
        'Tag Archive Page' => 'TAG',
        'Author Archive Page' => 'AUTHOR',
        'Date Archive Page' => 'DATE',
        'Search Page' => 'SEARCH',
        'Not Found (404) Page' => '404',
        'Single Post' => 'SINGLE'
        
    );

    $combined_pages = array_merge($combined_pages, $pages_array);
    return $combined_pages;
}