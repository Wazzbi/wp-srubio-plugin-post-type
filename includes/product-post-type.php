<?php
function _themename__pluginname_setup_post_type()
{
    $labels = array(
        'name' => esc_html_x('Catalog', 'Post type general name', '_themename-_pluginname'),
        'singular_name' => esc_html_x('Product', 'Post type singular name', '_themename-_pluginname'),
        'menu_name' => esc_html_x('Catalog', 'Admin Menu text', '_themename-_pluginname'),
        'name_admin_bar' => esc_html_x('Product', 'Add New on Toolbar', '_themename-_pluginname'),
        'add_new' => esc_html__('Add New', '_themename-_pluginname'),
        'add_new_item' => esc_html__('Add New Product', '_themename-_pluginname'),
        'new_item' => esc_html__('New Product', '_themename-_pluginname'),
        'edit_item' => esc_html__('Edit Product', '_themename-_pluginname'),
        'view_item' => esc_html__('View Product', '_themename-_pluginname'),
        'view_items' => esc_html__('View Products', '_themename-_pluginname'),
        'all_items' => esc_html__('All Products', '_themename-_pluginname'),
        'search_items' => esc_html__('Search Products', '_themename-_pluginname'),
        'parent_item_colon' => esc_html__('Parent Products:', '_themename-_pluginname'),
        'not_found' => esc_html__('No Products found.', '_themename-_pluginname'),
        'not_found_in_trash' => esc_html__('No Products found in Trash.', '_themename-_pluginname'),
        'featured_image' => esc_html_x('Product Image', 'Overrides the "Featured Image" phrase for this post type. Added in 4.3', '_themename-_pluginname'),
        'set_featured_image' => esc_html_x('Set product image', 'Overrides the "Set featured image" phrase for this post type. Added in 4.3', '_themename-_pluginname'),
        'remove_featured_image' => esc_html_x('Remove product image', 'Overrides the "Remove featured image" phrase for this post type. Added in 4.3', '_themename-_pluginname'),
        'use_featured_image' => esc_html_x('Use as product image', 'Overrides the "Use as featured image" phrase for this post type. Added in 4.3', '_themename-_pluginname'),
        'archives' => esc_html_x('Product archives', 'The post type archive label used in nav menus. Default "Post Archives". Added in 4.4.', '_themename-_pluginname'),
        'insert_into_item' => esc_html_x('Insert into product', 'Overrides the "Insert into post"/"Insert into page" phrase (used when inserting media into a post). Added in 4.4.', '_themename-_pluginname'),
        'uploaded_to_this_item' => esc_html_x('Uploaded to this product', 'Overrides the "Uploaded to this post"/"Uploaded to this page" phrase (used when viewing media attached to a post). Added in 4.4.', '_themename-_pluginname'),
        'filter_items_list' => esc_html_x('Filter product list', 'Screen reader text for the filter links heading on the post type listing screen. Default "Filter posts list"/"Filter pages list". Added in 4.4.', '_themename-_pluginname'),
        'items_list_navigation' => esc_html_x('Product list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default "Posts list navigation"/"Pages list navigation". Added in 4.4.', '_themename-_pluginname'),
        'items_list' => esc_html_x('Product list', 'Screen reader text for the items list heading on the post type listing screen. Default "Posts list"/"Pages list". Added in 4.4.', '_themename-_pluginname'),
    );

    register_post_type('_themename_product', array(
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-cart',
        'labels' => $labels,
        'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'custom-fields'),
        'rewrite' => array('slug' => 'products')
    ));
};
add_action('init', '_themename__pluginname_setup_post_type');

function _themename__pluginname_settings_post_type()
{
    add_submenu_page(
        'edit.php?post_type=_themename_product', // Parent slug (your CPT)
        'Nastavení společných prvků',              // Page title
        'Settings',                         // Menu title
        'manage_options',                   // Capability
        '_themename__pluginname_settings',           // Menu slug
        '_themename__pluginname_settings_page_html'       // Callback function to render the page
    );
}
add_action('admin_menu', '_themename__pluginname_settings_post_type');

/**
 * Renders the content for the CPT Settings page.
 */
function _themename__pluginname_settings_page_html()
{
    // Check user capabilities
    if (! current_user_can('manage_options')) {
        return;
    }

    settings_errors();

?>
    <div class="wrap">
        <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
        <form action="options.php" method="post">
            <?php
            // Security field and nonce for the form submission
            settings_fields('_themename__pluginname_product_settings_group');

            // Output all registered settings sections and fields
            do_settings_sections('_themename__pluginname_settings');

            // Submit button
            submit_button('Save Settings');
            ?>
        </form>
    </div>
<?php
}

/**
 * Registers CPT settings, sections, and fields.
 */
function _themename__pluginname_product_settings_init()
{

    // 1. Register a setting (a group of options to save)
    // The '_themename__pluginname_product_options' is the option_name saved in the database.
    register_setting(
        '_themename__pluginname_product_settings_group', // Settings group name
        '_themename__pluginname_product_options',      // Option name (will be a single array in wp_options)
        '_themename__pluginname_product_sanitize'                   // Optional: Sanitize callback function

    );

    // 2. Add a settings section
    add_settings_section(
        '_themename__pluginname_section_general',         // ID of the section
        'General Product Settings',                      // Title to display
        '_themename__pluginname_section_general_callback', // Callback function to render section description
        '_themename__pluginname_settings'                         // Page slug (from add_submenu_page)
    );

    // 3. Add a settings field (e.g., a checkbox for showing the price)
    add_settings_field(
        '_themename__pluginname_field_file_catalog',      // ID of the field
        'Show Product Price',                          // Label of the field
        '_themename__pluginname_field_file_catalog_html', // Callback function to render the input field HTML
        '_themename__pluginname_settings',                      // Page slug (where section is added)
        '_themename__pluginname_section_general',      // Section ID
        array(                                         // Optional: Arguments passed to the callback
            'label_for' => '_themename__pluginname_field_file_catalog',
            'class'     => 'file-catalog-row',
        )
    );

    add_settings_field(
        '_themename__pluginname_field_file_price_list',      // ID of the field
        'Show Product Price',                          // Label of the field
        '_themename__pluginname_field_file_price_list_html', // Callback function to render the input field HTML
        '_themename__pluginname_settings',                      // Page slug (where section is added)
        '_themename__pluginname_section_general',      // Section ID
        array(                                         // Optional: Arguments passed to the callback
            'label_for' => '_themename__pluginname_field_file_price_list',
            'class'     => 'file-price-list-row',
        )
    );

    add_settings_field(
        '_themename__pluginname_field_editor_content',           // **New Field ID**
        'Global Product Content',                               // **New Label**
        '_themename__pluginname_field_editor_content_html',      // **New Callback**
        '_themename__pluginname_settings',
        '_themename__pluginname_section_general',
        array(
            'label_for' => '_themename__pluginname_field_editor_content',
            'class'     => 'editor-content-row',
        )
    );
}
add_action('admin_init', '_themename__pluginname_product_settings_init');

/**
 * Renders the section description.
 */
function _themename__pluginname_section_general_callback()
{
    echo '<p>Configure the general display settings for your products.</p>';
}

/**
 * Sanitizes the input before saving to the database.
 * @param array $input The unsanitized input array.
 * @return array The sanitized array.
 */
function _themename__pluginname_product_sanitize($input)
{
    $new_input = array();

    // Check if the custom text field exists and sanitize it
    if (isset($input['_themename__pluginname_field_file_catalog'])) {
        $new_input['_themename__pluginname_field_file_catalog'] = sanitize_text_field($input['_themename__pluginname_field_file_catalog']);
    }

    // Check if the custom text field exists and sanitize it
    if (isset($input['_themename__pluginname_field_file_price_list'])) {
        $new_input['_themename__pluginname_field_file_price_list'] = sanitize_text_field($input['_themename__pluginname_field_file_price_list']);
    }

    // Sanitize the Rich Text Editor content using wp_kses_post()
    if (isset($input['_themename__pluginname_field_editor_content'])) {
        $new_input['_themename__pluginname_field_editor_content'] = wp_kses_post($input['_themename__pluginname_field_editor_content']);
    }

    return $new_input;
}

/**
 * Renders the HTML for the 'Show Product Price' checkbox field.
 */
function _themename__pluginname_field_file_catalog_html($args)
{
    // Retrieve the entire options array
    $options = get_option('_themename__pluginname_product_options');

    // Get the specific field value, default to 0 (unchecked) if not set
    $value = isset($options['_themename__pluginname_field_file_catalog']) ? $options['_themename__pluginname_field_file_catalog'] : 0;

?>
    <input
        type="text"
        id="<?php echo esc_attr($args['label_for']); ?>"
        name="_themename__pluginname_product_options[<?php echo esc_attr($args['label_for']); ?>]"
        value="<?php echo esc_attr($value); ?>"
        class="regular-text" />
    <p class="description">Check this box to display the price on the product detail page.</p>
<?php
}

/**
 * Renders the HTML for the 'Show Product Price' checkbox field.
 */
function _themename__pluginname_field_file_price_list_html($args)
{
    // Retrieve the entire options array
    $options = get_option('_themename__pluginname_product_options');

    // Get the specific field value, default to 0 (unchecked) if not set
    $value = isset($options['_themename__pluginname_field_file_price_list']) ? $options['_themename__pluginname_field_file_price_list'] : 0;

?>
    <input
        type="text"
        id="<?php echo esc_attr($args['label_for']); ?>"
        name="_themename__pluginname_product_options[<?php echo esc_attr($args['label_for']); ?>]"
        value="<?php echo esc_attr($value); ?>"
        class="regular-text" />
    <p class="description">Check this box to display the price on the product detail page.</p>
<?php
}

/**
 * Renders the HTML for the Rich Text Editor field using wp_editor().
 */
function _themename__pluginname_field_editor_content_html($args)
{
    // Retrieve the entire options array
    $options = get_option('_themename__pluginname_product_options');

    // Get the specific field value
    $value = isset($options[$args['label_for']]) ? $options[$args['label_for']] : '';

    // The name attribute needs to match the structure for saving:
    $editor_name = '_themename__pluginname_product_options[' . esc_attr($args['label_for']) . ']';

    // Settings for the editor (height, media buttons, etc.)
    $settings = array(
        'textarea_name' => $editor_name,
        'textarea_rows' => 10,
        'editor_class'  => 'rich-text-content',
        'media_buttons' => true, // Allow media uploads
    );

    // Output the WordPress rich text editor
    wp_editor($value, esc_attr($args['label_for']), $settings);

?>
    <p class="description">This content will be displayed globally on your product pages.</p>
<?php
}
