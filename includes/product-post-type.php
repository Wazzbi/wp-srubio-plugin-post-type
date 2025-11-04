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
        '_themename_post_types_field_file_catalog',      // ID of the field
        'Show Product Price',                          // Label of the field
        '_themename__pluginname_field_file_catalog_html', // Callback function to render the input field HTML
        '_themename__pluginname_settings',                      // Page slug (where section is added)
        '_themename__pluginname_section_general',      // Section ID
        array(                                         // Optional: Arguments passed to the callback
            'label_for' => '_themename_post_types_field_file_catalog',
            'class'     => 'file-catalog-row',
        )
    );

    add_settings_field(
        '_themename_post_types_field_file_price_list',      // ID of the field
        'Show Product Price',                          // Label of the field
        '_themename__pluginname_field_file_price_list_html', // Callback function to render the input field HTML
        '_themename__pluginname_settings',                      // Page slug (where section is added)
        '_themename__pluginname_section_general',      // Section ID
        array(                                         // Optional: Arguments passed to the callback
            'label_for' => '_themename_post_types_field_file_price_list',
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
 * Enqueue the WordPress media uploader scripts on the CPT settings page.
 */
function _themename__pluginname_enqueue_media_uploader_scripts($hook)
{
    // Only load on the specific settings page
    if ('toplevel_page__themename__pluginname_settings' !== $hook && 'product_page__themename__pluginname_settings' !== $hook) {
        // Adjust the hook check based on your actual page hook if needed.
        // For a submenu of a CPT 'edit.php?post_type=_themename_product', 
        // the hook usually is 'product_page_[your_menu_slug]'
        return;
    }

    // Enqueue the scripts for the WordPress media uploader
    wp_enqueue_media();
}
add_action('admin_enqueue_scripts', '_themename__pluginname_enqueue_media_uploader_scripts');

/**
 * Consolidated JavaScript for Media Uploader fields.
 * Hooks into admin_footer to ensure elements are present.
 */
function _themename__pluginname_media_uploader_script()
{
    //Check if we are on the correct settings page before outputting JS
    $screen = get_current_screen();
    if (!isset($screen->id) || ('_themename_product_page__themename__pluginname_settings' !== $screen->id)) {
        return;
    }
?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            var file_frame;
            // NEW: Variable to store the button that opened the modal
            var active_uploader_button;

            // UPLOAD BUTTON LOGIC (Handles both fields)
            $(document).on('click', '.upload-file-button', function(e) {
                e.preventDefault();

                // Store the clicked button in the global scope variable
                active_uploader_button = $(this);

                // If the media frame already exists, reopen it.
                if (file_frame) {
                    file_frame.open();
                    return;
                }

                // Create the media frame.
                file_frame = wp.media.frames.file_frame = wp.media({
                    title: active_uploader_button.data('uploader_title'),
                    button: {
                        text: active_uploader_button.data('uploader_button_text'),
                    },
                    multiple: false
                });

                // When a file is selected, run a callback.
                file_frame.on('select', function() {
                    var attachment = file_frame.state().get('selection').first().toJSON();

                    // Use the stored active_uploader_button to find the associated fields
                    var $field = active_uploader_button.prev('.media-file-id');
                    var $preview_wrap = active_uploader_button.nextAll('.file-preview-wrap');
                    var $remove_button = active_uploader_button.next('.remove-file-button');

                    // Update the elements relative to the currently active button
                    $field.val(attachment.id);
                    $preview_wrap.find('.file-url-display a').attr('href', attachment.url).text(attachment.url);
                    $preview_wrap.show();
                    $remove_button.show();
                    active_uploader_button.text('Change File');
                });

                // Finally, open the modal.
                file_frame.open();
            });

            // REMOVE BUTTON LOGIC (Handles both fields) - This part was already correct
            $(document).on('click', '.remove-file-button', function(e) {
                e.preventDefault();

                var $button = $(this);
                // Traverse the DOM relative to the clicked button to find the elements
                var $field = $button.prevAll('.media-file-id');
                var $upload_button = $button.prev('.upload-file-button');
                var $preview_wrap = $button.next('.file-preview-wrap');

                // Clear the hidden input
                $field.val('');

                // Hide and reset display
                $preview_wrap.hide();
                $button.hide();
                $upload_button.text('Select File');
            });
        });
    </script>
<?php
}
add_action('admin_footer', '_themename__pluginname_media_uploader_script');

/**
 * Sanitizes the input before saving to the database.
 * @param array $input The unsanitized input array.
 * @return array The sanitized array.
 */
function _themename__pluginname_product_sanitize($input)
{
    $new_input = array();

    // Check if the file catalog field exists and sanitize it as an integer (Media ID)
    if (isset($input['_themename_post_types_field_file_catalog'])) {
        // Use absint to ensure a non-negative integer ID
        $new_input['_themename_post_types_field_file_catalog'] = absint($input['_themename_post_types_field_file_catalog']);
    }

    // Check if the custom text field exists and sanitize it
    if (isset($input['_themename_post_types_field_file_price_list'])) {
        $new_input['_themename_post_types_field_file_price_list'] = absint($input['_themename_post_types_field_file_price_list']);
    }

    // Sanitize the Rich Text Editor content using wp_kses_post()
    if (isset($input['_themename__pluginname_field_editor_content'])) {
        $new_input['_themename__pluginname_field_editor_content'] = wp_kses_post($input['_themename__pluginname_field_editor_content']);
    }

    return $new_input;
}

/**
 * Renders the HTML for the Media File Catalog selector field.
 */
function _themename__pluginname_field_file_catalog_html($args)
{
    // Retrieve the entire options array
    $options = get_option('_themename__pluginname_product_options');

    // Get the specific field value (Media ID), default to empty
    $value = isset($options['_themename_post_types_field_file_catalog']) ? $options['_themename_post_types_field_file_catalog'] : '';

    // Get the URL of the selected file for display (if an ID is saved)
    $file_url = $value ? wp_get_attachment_url($value) : '';

    $field_id = esc_attr($args['label_for']);
    $input_name = '_themename__pluginname_product_options[' . $field_id . ']';

    $button_text = $file_url ? 'Change File' : 'Select File';
    $remove_text = 'Remove File';
    $display_style = $file_url ? 'style="display: inline-block;"' : 'style="display: none;"';
?>
    <input
        type="hidden"
        id="<?php echo $field_id; ?>"
        name="<?php echo $input_name; ?>"
        value="<?php echo esc_attr($value); ?>"
        class="regular-text media-file-id" />

    <button type="button" class="button button-secondary upload-file-button"
        data-uploader_title="Select Catalog File"
        data-uploader_button_text="Use this file">
        <?php echo esc_html($button_text); ?>
    </button>

    <button type="button" class="button button-secondary remove-file-button" <?php echo $display_style; ?>>
        <?php echo esc_html($remove_text); ?>
    </button>

    <div class="file-preview-wrap" <?php echo $display_style; ?>>
        <p class="file-url-display">Selected File: <a href="<?php echo esc_url($file_url); ?>" target="_blank"><?php echo esc_html($file_url); ?></a></p>
    </div>

    <p class="description">Select a file from the media library to use as a downloadable catalog.</p>
<?php
}

/**
 * Renders the HTML for the Media File Price List selector field.
 */
function _themename__pluginname_field_file_price_list_html($args)
{
    // Retrieve the entire options array
    $options = get_option('_themename__pluginname_product_options');

    // Get the specific field value (Media ID), default to empty
    $value = isset($options['_themename_post_types_field_file_price_list']) ? $options['_themename_post_types_field_file_price_list'] : '';

    // Get the URL of the selected file for display (if an ID is saved)
    $file_url = $value ? wp_get_attachment_url($value) : '';

    $field_id = esc_attr($args['label_for']);
    $input_name = '_themename__pluginname_product_options[' . $field_id . ']';

    $button_text = $file_url ? 'Change File' : 'Select File';
    $remove_text = 'Remove File';
    $display_style = $file_url ? 'style="display: inline-block;"' : 'style="display: none;"';
?>
    <input
        type="hidden"
        id="<?php echo $field_id; ?>"
        name="<?php echo $input_name; ?>"
        value="<?php echo esc_attr($value); ?>"
        class="regular-text media-file-id" />

    <button type="button" class="button button-secondary upload-file-button"
        data-uploader_title="Select Price List File"
        data-uploader_button_text="Use this file">
        <?php echo esc_html($button_text); ?>
    </button>

    <button type="button" class="button button-secondary remove-file-button" <?php echo $display_style; ?>>
        <?php echo esc_html($remove_text); ?>
    </button>

    <div class="file-preview-wrap" <?php echo $display_style; ?>>
        <p class="file-url-display">Selected File: <a href="<?php echo esc_url($file_url); ?>" target="_blank"><?php echo esc_html($file_url); ?></a></p>
    </div>

    <p class="description">Select a file from the media library to use as a downloadable price list.</p>

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
