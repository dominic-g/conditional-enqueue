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


function conditional_enqueue_scripts() {


    $current_screen = get_current_screen();
    if ($current_screen && $current_screen->id === 'settings_page_conditional-enqueue-settings') {
        wp_enqueue_script('conditional-enqueue-admin', plugin_dir_url(__FILE__) . 'js/admin.js', array('jquery'), '1.0', true);
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
    // $pages = get_pages();
    ?>
     <form name="countries" class="form" id="form">
         <div class="form-group">
            <span class="form-arrow"><i class="bx bx-chevron-down">
                <svg width="24px" height="24px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <rect x="0" fill="none" width="24" height="24"/>
                    <g>
                    <path d="M7 10l5 5 5-5"/>
                    </g>
                </svg>
            </i></span>
            <select name="country" id="country" class="dropdown">
               <option disabled>Select your country</option>
               <option value="Afghanistan">Afghanistan</option>
               <option value="Albania">Albania</option>
               <option value="Algeria">Algeria</option>
               <option value="American Samoa">American Samoa</option>
               <option value="Andorra">Andorra</option>
               <option value="Angola">Angola</option>
               <option value="Anguilla">Anguilla</option>
               <option value="Antigua Barbuda">Antigua Barbuda</option>
               <option value="Argentina">Argentina</option>
               <option value="Armenia">Armenia</option>
               <option value="Aruba">Aruba</option>
               <option value="Australia">Australia</option>
               <option value="Austria">Austria</option>
               <option value="Azerbaijan">Azerbaijan</option>
               <option value="Bahamas">Bahamas</option>
               <option value="Bahrain">Bahrain</option>
               <option value="Bangladesh">Bangladesh</option>
               <option value="Barbados">Barbados</option>
               <option value="Belarus">Belarus</option>
               <option value="Belgium">Belgium</option>
               <option value="Belize">Belize</option>
               <option value="Benin">Benin</option>
               <option value="Bermuda">Bermuda</option>
               <option value="Bhutan">Bhutan</option>
               <option value="Bolivia">Bolivia</option>
               <option value="Bonaire">Bonaire</option>
               <option value="Bosnia Herzegovina">Bosnia Herzegovina</option>
               <option value="Botswana">Botswana</option>
               <option value="Brazil">Brazil</option>
               <option value="Brunei">Brunei</option>
               <option value="Bulgaria">Bulgaria</option>
               <option value="Burundi">Burundi</option>
               <option value="Cambodia">Cambodia</option>
               <option value="Cameroon">Cameroon</option>
               <option value="Canada">Canada</option>
               <option value="Central African">Central African</option>
               <option value="Chad">Chad</option>
               <option value="Chile">Chile</option>
               <option value="China">China</option>
               <option value="Colombia">Colombia</option>
               <option value="Comoros">Comoros</option>
               <option value="Congo">Congo</option>
               <option value="Croatia">Croatia</option>
               <option value="Cuba">Cuba</option>
               <option value="Curacao">Curacao</option>
               <option value="Cyprus">Cyprus</option>
               <option value="Czech">Czech</option>
               <option value="Denmark">Denmark</option>
               <option value="Djibouti">Djibouti</option>
               <option value="Dominica">Dominica</option>
               <option value="East Timor">East Timor</option>
               <option value="Ecuador">Ecuador</option>
               <option value="Egypt">Egypt</option>
               <option value="Eritrea">Eritrea</option>
               <option value="Estonia">Estonia</option>
               <option value="Ethiopia">Ethiopia</option>
               <option value="Finland">Finland</option>
               <option value="France">France</option>
               <option value="Gabon">Gabon</option>
               <option value="Gambia">Gambia</option>
               <option value="Georgia">Georgia</option>
               <option value="Germany">Germany</option>
               <option value="Ghana">Ghana</option>
               <option value="Gibraltar">Gibraltar</option>
               <option value="Greece">Greece</option>
               <option value="Greenland">Greenland</option>
               <option value="Grenada">Grenada</option>
               <option value="Guadeloupe">Guadeloupe</option>
               <option value="Guam">Guam</option>
               <option value="Guatemala">Guatemala</option>
               <option value="Guinea">Guinea</option>
               <option value="Guyana">Guyana</option>
               <option value="Haiti">Haiti</option>
               <option value="Hawaii">Hawaii</option>
               <option value="Honduras">Honduras</option>
               <option value="Hongkong">Hongkong</option>
               <option value="Hungary">Hungary</option>
               <option value="Iceland">Iceland</option>
               <option value="Indonesia">Indonesia</option>
               <option value="India">India</option>
               <option value="Iran">Iran</option>
               <option value="Iraq">Iraq</option>
               <option value="Ireland">Ireland</option>
               <option value="Israel">Israel</option>
               <option value="Italy">Italy</option>
               <option value="Jamaica">Jamaica</option>
               <option value="Japan">Japan</option>
               <option value="Jordan">Jordan</option>
               <option value="Kazakhstan">Kazakhstan</option>
               <option value="Kenya">Kenya</option>
               <option value="Kiribati">Kiribati</option>
               <option value="Korea North">Korea North</option>
               <option value="Korea South">Korea South</option>
               <option value="Kuwait">Kuwait</option>
               <option value="Kyrgyzstan">Kyrgyzstan</option>
               <option value="Laos">Laos</option>
               <option value="Latvia">Latvia</option>
               <option value="Lebanon">Lebanon</option>
               <option value="Lesotho">Lesotho</option>
               <option value="Liberia">Liberia</option>
               <option value="Libya">Libya</option>
               <option value="Liechtenstein">Liechtenstein</option>
               <option value="Lithuania">Lithuania</option>
               <option value="Luxembourg">Luxembourg</option>
               <option value="Macau">Macau</option>
               <option value="Macedonia">Macedonia</option>
               <option value="Madagascar">Madagascar</option>
               <option value="Malaysia">Malaysia</option>
               <option value="Malawi">Malawi</option>
               <option value="Maldives">Maldives</option>
               <option value="Mali">Mali</option>
               <option value="Malta">Malta</option>
               <option value="Martinique">Martinique</option>
               <option value="Mauritania">Mauritania</option>
               <option value="Mauritius">Mauritius</option>
               <option value="Mayotte">Mayotte</option>
               <option value="Mexico">Mexico</option>
               <option value="Moldova">Moldova</option>
               <option value="Monaco">Monaco</option>
               <option value="Mongolia">Mongolia</option>
               <option value="Montserrat">Montserrat</option>
               <option value="Morocco">Morocco</option>
               <option value="Mozambique">Mozambique</option>
               <option value="Myanmar">Myanmar</option>
               <option value="Namibia">Namibia</option>
               <option value="Nauru">Nauru</option>
               <option value="Nepal">Nepal</option>
               <option value="Netherlands">Netherlands</option>
               <option value="Nevis">Nevis</option>
               <option value="New Caledonia">New Caledonia</option>
               <option value="New Zealand">New Zealand</option>
               <option value="Nicaragua">Nicaragua</option>
               <option value="Niger">Niger</option>
               <option value="Nigeria">Nigeria</option>
               <option value="Norway">Norway</option>
               <option value="Oman">Oman</option>
               <option value="Pakistan">Pakistan</option>
               <option value="Palestine">Palestine</option>
               <option value="Panama">Panama</option>
               <option value="Papua New Guinea">Papua New Guinea</option>
               <option value="Paraguay">Paraguay</option>
               <option value="Peru">Peru</option>
               <option value="Philippines">Philippines</option>
               <option value="Poland">Poland</option>
               <option value="Portugal">Portugal</option>
               <option value="Puerto Rico">Puerto Rico</option>
               <option value="Qatar">Qatar</option>
               <option value="Montenegro">Montenegro</option>
               <option value="Serbia">Serbia</option>
               <option value="Reunion">Reunion</option>
               <option value="Romania">Romania</option>
               <option value="Russia">Russia</option>
               <option value="Rwanda">Rwanda</option>
               <option value="Saipan">Saipan</option>
               <option value="Samoa">Samoa</option>
               <option value="Saudi Arabia">Saudi Arabia</option>
               <option value="Senegal">Senegal</option>
               <option value="Seychelles">Seychelles</option>
               <option value="Sierra Leone">Sierra Leone</option>
               <option value="Singapore">Singapore</option>
               <option value="Slovakia">Slovakia</option>
               <option value="Slovenia">Slovenia</option>
               <option value="Solomon">Solomon</option>
               <option value="Somalia">Somalia</option>
               <option value="South Africa">South Africa</option>
               <option value="Spain">Spain</option>
               <option value="Sri Lanka">Sri Lanka</option>
               <option value="Sudan">Sudan</option>
               <option value="Suriname">Suriname</option>
               <option value="Swaziland">Swaziland</option>
               <option value="Sweden">Sweden</option>
               <option value="Switzerland">Switzerland</option>
               <option value="Syria">Syria</option>
               <option value="Tahiti">Tahiti</option>
               <option value="Taiwan">Taiwan</option>
               <option value="Tajikistan">Tajikistan</option>
               <option value="Tanzania">Tanzania</option>
               <option value="Thailand">Thailand</option>
               <option value="Togo">Togo</option>
               <option value="Tonga">Tonga</option>
               <option value="Tunisia">Tunisia</option>
               <option value="Turkey">Turkey</option>
               <option value="Turkmenistan">Turkmenistan</option>
               <option value="Tuvalu">Tuvalu</option>
               <option value="Uganda">Uganda</option>
               <option value="United Kingdom">United Kingdom</option>
               <option value="Ukraine">Ukraine</option>
               <option value="United Arab Emirates">United Arab Emirates</option>
               <option value="United States America">United States America</option>
               <option value="Uruguay">Uruguay</option>
               <option value="Uzbekistan">Uzbekistan</option>
               <option value="Vanuatu">Vanuatu</option>
               <option value="Vatican">Vatican</option>
               <option value="Venezuela">Venezuela</option>
               <option value="Vietnam">Vietnam</option>
               <option value="Yemen">Yemen</option>
               <option value="Zaire">Zaire</option>
               <option value="Zambia">Zambia</option>
               <option value="Zimbabwe">Zimbabwe</option>
            </select>
         </div>
      </form>
    <?php
}
function conditional_enqueue_settings_page_select_() {
    // $pages = get_pages();
    $pages = retrive_all_pages();
    if(count($pages) > 0){
        $options = get_option('conditional_enqueue_settings');
        echo '<select name="conditional_enqueue_settings[page_select]" class="conditional_enqueue_settings">';
        echo '<option disabled selected>Select Page</option>';
        foreach ($pages as $key => $page) {
            $selected = (isset($options['page_select']) && is_object($page) && property_exists($page, 'ID') && $options['page_select'] == $page->ID) ? 'selected' : '';
            echo "<option value='".esc_attr($key, 'conditional-enqueue')."' ".esc_html($selected, 'conditional-enqueue').">".esc_html($key, 'conditional-enqueue')."</option>";
        }
        echo '</select>';

        // print_r(retrieve_assets());
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