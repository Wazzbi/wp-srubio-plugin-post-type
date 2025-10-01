<?php

/**
 * ========================================================
 * PRODUCT BRAND TAXONOMY (NON-HIERARCHICAL, SINGLE-SELECT, WITH IMAGE)
 * ========================================================
 */
function _themename__pluginname_register_product_brand_tax()
{
    // ... (Labels remain the same) ...
    $labels = array(
        'name'                         => esc_html_x('Brands', 'Taxonomy General Name', '_themename-_pluginname'),
        'singular_name'                => esc_html_x('Brand', 'Taxonomy Singular Name', '_themename-_pluginname'),
        'menu_name'                    => esc_html__('Brands', '_themename-_pluginname'),
        'all_items'                    => esc_html__('All Brands', '_themename-_pluginname'),
        'edit_item'                    => esc_html__('Edit Brand', '_themename-_pluginname'),
        'view_item'                    => esc_html__('View Brand', '_themename-_pluginname'),
        'update_item'                  => esc_html__('Update Brand', '_themename-_pluginname'),
        'add_new_item'                 => esc_html__('Add New Brand', '_themename-_pluginname'),
        'new_item_name'                => esc_html__('New Brand Name', '_themename-_pluginname'),
        'parent_item'                  => null,
        'parent_item_colon'            => null,
        'search_items'                 => esc_html__('Search Brands', '_themename-_pluginname'),
        'popular_items'                => esc_html__('Popular Brands', '_themename-_pluginname'),
        'separate_items_with_commas'   => esc_html__('Separate brands with commas', '_themename-_pluginname'),
        'add_or_remove_items'          => esc_html__('Add or remove brands', '_themename-_pluginname'),
        'choose_from_most_used'        => esc_html__('Choose from the most used brands', '_themename-_pluginname'),
        'not_found'                    => esc_html__('No Brands Found', '_themename-_pluginname'),
        'no_terms'                     => esc_html__('No brands', '_themename-_pluginname'),
        'items_list'                   => esc_html__('Brands list', '_themename-_pluginname'),
        'items_list_navigation'        => esc_html__('Brands list navigation', '_themename-_pluginname'),
    );

    $args = array(
        'labels'                       => $labels,
        'hierarchical'                 => false,
        'public'                       => true,
        'show_ui'                      => true,
        'show_admin_column'            => true,
        'show_in_nav_menus'            => true,
        'show_tagcloud'                => false,
        'rewrite'                      => array('slug' => 'brand', 'hierarchical' => false),
        'meta_box_cb'                  => false,
    );

    register_taxonomy('_themename_product_brand', ['_themename_product'], $args);
};
add_action('init', '_themename__pluginname_register_product_brand_tax');


// -----------------------------------------------------------------------------
// A. IMAGE FIELD FUNCTIONS (ADMIN TERM SCREEN) - These are for the main Brands page
// -----------------------------------------------------------------------------

function _themename_product_brand_add_form_fields()
{
?>
    <div class="form-field term-image-wrap">
        <label for="term_image_id"><?php esc_html_e('Brand Image', '_themename-_pluginname'); ?></label>
        <input type="hidden" id="term_image_id" name="term_image_id" value="">
        <div id="term_image_preview"></div>
        <p>
            <a href="#" class="button upload_image_button"><?php esc_html_e('Upload/Add Image', '_themename-_pluginname'); ?></a>
            <a href="#" class="button remove_image_button" style="display:none;"><?php esc_html_e('Remove Image', '_themename-_pluginname'); ?></a>
        </p>
    </div>
<?php
}
add_action('_themename_product_brand_add_form_fields', '_themename_product_brand_add_form_fields');

function _themename_product_brand_edit_form_fields($term)
{
    $image_id = get_term_meta($term->term_id, 'term_image_id', true);
    $image_url = $image_id ? wp_get_attachment_url($image_id) : '';
?>
    <tr class="form-field term-image-wrap">
        <th scope="row"><label for="term_image_id"><?php esc_html_e('Brand Image', '_themename-_pluginname'); ?></label></th>
        <td>
            <input type="hidden" id="term_image_id" name="term_image_id" value="<?php echo esc_attr($image_id); ?>">
            <div id="term_image_preview">
                <?php if ($image_url) { ?>
                    <img src="<?php echo esc_url($image_url); ?>" alt="" style="max-width:150px; height:auto;">
                <?php } ?>
            </div>
            <p>
                <a href="#" class="button upload_image_button"><?php esc_html_e('Upload/Add Image', '_themename-_pluginname'); ?></a>
                <a href="#" class="button remove_image_button" style="<?php echo $image_url ? '' : 'display:none;'; ?>"><?php esc_html_e('Remove Image', '_themename-_pluginname'); ?></a>
            </p>
        </td>
    </tr>
<?php
}
add_action('_themename_product_brand_edit_form_fields', '_themename_product_brand_edit_form_fields');

/* Save the image ID when a term is created or edited on the main admin page. */
function _themename_save_product_brand_image($term_id)
{
    if (isset($_POST['term_image_id'])) {
        $image_id = absint($_POST['term_image_id']);
        update_term_meta($term_id, 'term_image_id', $image_id);
    }
}
add_action('created__themename_product_brand', '_themename_save_product_brand_image');
add_action('edited__themename_product_brand', '_themename_save_product_brand_image');

/* Add image column to admin table. */
function _themename_add_brand_image_column($columns)
{
    $new_columns = array();
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        if ($key === 'name') {
            $new_columns['_themename_product_brand_image'] = esc_html__('Image', '_themename-_pluginname');
        }
    }
    return $new_columns;
}
add_filter('manage_edit-_themename_product_brand_columns', '_themename_add_brand_image_column');

function _themename_show_brand_image_column($content, $column_name, $term_id)
{
    if ('_themename_product_brand_image' === $column_name) {
        $image_id = get_term_meta($term_id, 'term_image_id', true);
        if ($image_id) {
            $image_url = wp_get_attachment_image_url($image_id, 'thumbnail');
            if ($image_url) {
                $content = '<img src="' . esc_url($image_url) . '" alt="" style="max-width: 50px; height: auto;" />';
            }
        }
    }
    return $content;
}
add_action('manage__themename_product_brand_custom_column', '_themename_show_brand_image_column', 10, 3);


// -----------------------------------------------------------------------------
// B. CUSTOM META BOX FUNCTIONS (PRODUCT EDIT SCREEN)
// -----------------------------------------------------------------------------

/**
 * Add custom metabox (removes default tag input and replaces with radio buttons).
 */
function _themename_custom_brand_metabox()
{
    $taxonomy_slug = '_themename_product_brand';
    $post_type = '_themename_product';
    remove_meta_box('tagsdiv-' . $taxonomy_slug, $post_type, 'side');
    remove_meta_box($taxonomy_slug . 'div', $post_type, 'side');

    add_meta_box(
        $taxonomy_slug . '_metabox',
        esc_html__('Product Brand', '_themename-_pluginname'),
        '_themename_product_brand_metabox_callback',
        $post_type,
        'side',
        'core'
    );
}
add_action('add_meta_boxes', '_themename_custom_brand_metabox');


/**
 * The callback function to display the radio button list and the Add New form.
 * ⚠️ FIX APPLIED HERE: Changed the input name from 'tax_input[...]' to '_themename_product_brand_select'
 */
function _themename_product_brand_metabox_callback($post)
{
    $taxonomy_slug = '_themename_product_brand';
    // Define the new custom input name for the radio group
    $custom_input_name = '_themename_product_brand_select';
    $post_terms = wp_get_post_terms($post->ID, $taxonomy_slug, array('fields' => 'ids'));
    $selected_id = !is_wp_error($post_terms) && !empty($post_terms) ? $post_terms[0] : 0;
    $all_terms = get_terms(array(
        'taxonomy'   => $taxonomy_slug,
        'hide_empty' => false,
        'orderby'    => 'name',
        'order'      => 'ASC',
    ));
    wp_nonce_field($taxonomy_slug . '_nonce', $taxonomy_slug . '_nonce_field');

?>
    <div id="taxonomy-<?php echo esc_attr($taxonomy_slug); ?>" class="categorydiv">
        <ul class="category-tabs">
            <li class="tabs"><a href="#<?php echo esc_attr($taxonomy_slug); ?>-all"><?php esc_html_e('All Brands', '_themename-_pluginname'); ?></a></li>
        </ul>

        <div id="<?php echo esc_attr($taxonomy_slug); ?>-all" class="tabs-panel">
            <ul id="<?php echo esc_attr($taxonomy_slug); ?>checklist" class="categorychecklist form-no-clear">
                <?php foreach ($all_terms as $term) : ?>
                    <li id="term-<?php echo esc_attr($term->term_id); ?>">
                        <label class="selectit">
                            <input value="<?php echo esc_attr($term->term_id); ?>" type="radio"
                                name="<?php echo esc_attr($custom_input_name); ?>" 👈 **FIXED: Changed name**
                                id="in-<?php echo esc_attr($taxonomy_slug); ?>-<?php echo esc_attr($term->term_id); ?>"
                                <?php checked($selected_id, $term->term_id); ?> />
                            <?php echo esc_html($term->name); ?>
                        </label>
                    </li>
                <?php endforeach; ?>
                <li>
                    <label class="selectit">
                        <input value="0" type="radio"
                            name="<?php echo esc_attr($custom_input_name); ?>" 👈 **FIXED: Changed name**
                            id="in-<?php echo esc_attr($taxonomy_slug); ?>-0"
                            <?php checked(0, $selected_id); ?> />
                        <?php esc_html_e('— No Brand —', '_themename-_pluginname'); ?>
                    </label>
                </li>
            </ul>
        </div>

        <div id="<?php echo esc_attr($taxonomy_slug); ?>-adder" class="wp-hidden-no-js category-adder">
            <h4>
                <a id="<?php echo esc_attr($taxonomy_slug); ?>-add-toggle" href="#<?php echo esc_attr($taxonomy_slug); ?>-add" class="hide-if-no-js taxonomy-add-new">
                    <?php esc_html_e('+ Add New Brand', '_themename-_pluginname'); ?>
                </a>
            </h4>
            <div id="<?php echo esc_attr($taxonomy_slug); ?>-add" class="category-add" style="display: none;">
                <label class="screen-reader-text" for="new_<?php echo esc_attr($taxonomy_slug); ?>"><?php esc_html_e('Add New Brand', '_themename-_pluginname'); ?></label>
                <input type="text" name="new_<?php echo esc_attr($taxonomy_slug); ?>" id="new_<?php echo esc_attr($taxonomy_slug); ?>" class="form-required form-input-tip" value="" aria-required="true" />

                <div class="form-field term-image-wrap">
                    <input type="hidden" id="new_brand_image_id" name="new_brand_image_id" value="">
                    <div id="new_term_image_preview"></div>
                    <p>
                        <a href="#" class="button upload_image_button"><?php esc_html_e('Upload/Add Image', '_themename-_pluginname'); ?></a>
                        <a href="#" class="button remove_image_button" style="display:none;"><?php esc_html_e('Remove Image', '_themename-_pluginname'); ?></a>
                    </p>
                </div>

                <input type="button" id="<?php echo esc_attr($taxonomy_slug); ?>-add-submit" class="button category-add-submit" value="<?php esc_html_e('Add New Brand', '_themename-_pluginname'); ?>">
                <?php wp_nonce_field('add-brand', $taxonomy_slug . '_ajax_nonce', false); ?>
                <span id="ajax-response"></span>
            </div>
        </div>
    </div>

    <script>
        jQuery(document).ready(function($) {
            var taxonomy = '<?php echo esc_js($taxonomy_slug); ?>';
            var custom_input_name = '<?php echo esc_js($custom_input_name); ?>'; // Use the new name
            var checklist = $('#' + taxonomy + 'checklist');
            var media_frame;

            // --- 1. MEDIA UPLOADER LOGIC for the new form ---
            // ... (Media Uploader logic remains the same) ...
            $(document).on('click', '#' + taxonomy + '-adder .upload_image_button', function(e) {
                e.preventDefault();
                var $button = $(this);
                var $container = $button.closest('.term-image-wrap');
                var $imageIdInput = $container.find('#new_brand_image_id');
                var $preview = $container.find('#new_term_image_preview');
                var $removeButton = $container.find('.remove_image_button');

                if (media_frame) {
                    media_frame.open();
                    return;
                }

                media_frame = wp.media({
                    title: '<?php esc_html_e('Choose Brand Image', '_themename-_pluginname'); ?>',
                    button: {
                        text: '<?php esc_html_e('Use this image', '_themename-_pluginname'); ?>'
                    },
                    multiple: false
                });

                media_frame.on('select', function() {
                    var attachment = media_frame.state().get('selection').first().toJSON();
                    $imageIdInput.val(attachment.id);
                    $preview.html('<img src="' + attachment.url + '" alt="" style="max-width:150px; height:auto;">');
                    $removeButton.show();
                });

                media_frame.open();
            });

            $(document).on('click', '#' + taxonomy + '-adder .remove_image_button', function(e) {
                e.preventDefault();
                var $button = $(this);
                var $container = $button.closest('.term-image-wrap');
                $container.find('#new_brand_image_id').val('');
                $container.find('#new_term_image_preview').html('');
                $button.hide();
            });
            // --- END MEDIA UPLOADER LOGIC ---

            // 2. Toggle the 'Add New Brand' form
            $('#' + taxonomy + '-add-toggle').on('click', function(event) {
                event.preventDefault();
                $('#' + taxonomy + '-add').toggle();
                $('#new_' + taxonomy).focus();
            });

            // 3. Handle adding a new brand via custom AJAX
            $('#' + taxonomy + '-add-submit').on('click', function(event) {
                event.preventDefault();

                var newTermInput = $('#new_' + taxonomy);
                var newImageId = $('#new_brand_image_id').val();
                var nonceInput = $('input[name="' + taxonomy + '_ajax_nonce"]');
                var submitButton = $(this);
                var spinner = $('#ajax-response');

                spinner.empty();

                if (newTermInput.val().trim() === '') {
                    newTermInput.focus();
                    return;
                }

                spinner.html('<div class="spinner is-active" style="float:none;"></div>');
                submitButton.prop('disabled', true);

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'add_product_brand_ajax',
                        _ajax_nonce: nonceInput.val(),
                        taxonomy: taxonomy,
                        term_name: newTermInput.val(),
                        term_image_id: newImageId
                    },
                    success: function(response) {
                        spinner.find('.spinner').remove();
                        if (response.success) {
                            var newTermId = response.data.term_id;
                            var newTermName = response.data.term_name;

                            // Note: We use custom_input_name here
                            var newListItem = '<li id="term-' + newTermId + '">' +
                                '<label class="selectit">' +
                                '<input value="' + newTermId + '" type="radio" name="' + custom_input_name + '" ' +
                                'id="in-' + taxonomy + '-' + newTermId + '" checked="checked" /> ' +
                                newTermName + '</label></li>';

                            checklist.append(newListItem);

                            // Selects the newly added radio button and deselects all others with the custom name
                            checklist.find('input[name="' + custom_input_name + '"]').not('#in-' + taxonomy + '-' + newTermId).prop('checked', false);

                            // Reset logic
                            var $imageContainer = $('#' + taxonomy + '-adder');
                            $imageContainer.find('#new_brand_image_id').val('');
                            $imageContainer.find('#new_term_image_preview').html('');
                            $imageContainer.find('.remove_image_button').hide();
                            newTermInput.val('');

                            // Highlighting and focusing
                            $('#term-' + newTermId).css('background-color', '#ffffa1ff').animate({
                                'background-color': 'transparent'
                            }, 2000, function() {
                                $(this).css('background-color', '');
                            });
                            newTermInput.focus();

                        } else {
                            spinner.html('<p class="error">' + response.data.message + '</p>');
                        }
                        submitButton.prop('disabled', false);
                    },
                    error: function() {
                        spinner.html('<p class="error">An unknown error occurred.</p>');
                        submitButton.prop('disabled', false);
                    }
                });
            });
        });
    </script>
<?php
}

/**
 * Save the single term from the custom radio button brand metabox.
 * ⚠️ FIX APPLIED HERE: Changed the key we look for in $_POST from 'tax_input[...]' to '_themename_product_brand_select'
 */
function _themename_save_product_brand_metabox($post_id)
{
    $taxonomy_slug = '_themename_product_brand';
    $input_name = '_themename_product_brand_select'; // The new custom input name

    if (!current_user_can('edit_post', $post_id) || wp_is_post_revision($post_id)) {
        return;
    }

    if (!isset($_POST[$taxonomy_slug . '_nonce_field']) || !wp_verify_nonce($_POST[$taxonomy_slug . '_nonce_field'], $taxonomy_slug . '_nonce')) {
        return;
    }

    // Check for the custom input name
    if (isset($_POST[$input_name])) {
        $term_id = absint($_POST[$input_name]);

        if ($term_id > 0) {
            // Assign the single selected term by its ID
            wp_set_post_terms($post_id, array($term_id), $taxonomy_slug, false);
        } else {
            // If '0' is selected (No Brand), remove all terms
            wp_set_post_terms($post_id, array(), $taxonomy_slug, false);
        }
    }
}
add_action('save_post', '_themename_save_product_brand_metabox');


/**
 * AJAX handler to add a new brand term and save the image metadata.
 */
function _themename_add_product_brand_ajax()
{
    // The taxonomy slug defined in register_taxonomy
    $taxonomy = '_themename_product_brand';

    // Security check
    if (!current_user_can('manage_categories') || !check_ajax_referer('add-brand', '_ajax_nonce', false)) {
        wp_send_json_error(['message' => 'Security check failed.']);
    }

    $term_name = sanitize_text_field($_POST['term_name']);
    // CRITICAL: Retrieve the image ID sent from the JS
    $image_id = isset($_POST['term_image_id']) ? absint($_POST['term_image_id']) : 0;

    if (!taxonomy_exists($taxonomy) || empty($term_name)) {
        wp_send_json_error(['message' => 'Invalid term name or taxonomy.']);
    }

    // Attempt to insert the term
    $result = wp_insert_term($term_name, $taxonomy);

    if (is_wp_error($result)) {
        wp_send_json_error(['message' => $result->get_error_message()]);
    } else {
        $term_id = $result['term_id'];

        // CRITICAL FIX: Save the image meta immediately after successful term creation
        if ($image_id > 0) {
            // 'term_image_id' is the meta key used to store the image ID
            update_term_meta($term_id, 'term_image_id', $image_id);
        }

        // Return success response to the JavaScript
        wp_send_json_success([
            'term_id' => $term_id,
            'term_name' => $term_name,
        ]);
    }
    // AJAX handlers must terminate with wp_die()
    wp_die();
}
add_action('wp_ajax_add_product_brand_ajax', '_themename_add_product_brand_ajax');

/**
 * Add the custom Brand dropdown to the Quick Edit screen.
 */
function _themename_product_brand_quick_edit_custom_box($column_name, $post_type)
{
    // Check if we are on the correct post type and the column matches your taxonomy slug
    $taxonomy_slug = '_themename_product_brand';
    $post_type_slug = '_themename_product';
    $custom_column_name = 'taxonomy-' . $taxonomy_slug; // The default taxonomy column key

    if ($post_type !== $post_type_slug || $column_name !== $custom_column_name) {
        return;
    }

    $terms = get_terms([
        'taxonomy'   => $taxonomy_slug,
        'hide_empty' => false,
        'orderby'    => 'name',
        'order'      => 'ASC',
    ]);

?>
    <fieldset class="inline-edit-col-right inline-edit-<?php echo esc_attr($taxonomy_slug); ?>">
        <div class="inline-edit-col">
            <label class="inline-edit-group">
                <span class="title"><?php esc_html_e('Brand', '_themename-_pluginname'); ?></span>
                <select name="<?php echo esc_attr($taxonomy_slug); ?>_quick_edit_select" class="post_brand_select">
                    <option value="-1"><?php esc_html_e('— No Brand —', '_themename-_pluginname'); ?></option>
                    <?php
                    // Display each term as an option
                    foreach ($terms as $term) {
                        printf(
                            '<option value="%s">%s</option>',
                            esc_attr($term->term_id),
                            esc_html($term->name)
                        );
                    }
                    ?>
                </select>
                <input type="hidden" name="old_brand_terms" class="old_brand_terms" data-wp-taxonomy="<?php echo esc_attr($taxonomy_slug); ?>" value="">
            </label>
        </div>
    </fieldset>
<?php
}
add_action('quick_edit_custom_box', '_themename_product_brand_quick_edit_custom_box', 1, 2);

/**
 * Collects all brand data for the displayed posts and injects it as a global JS object.
 */
function _themename_product_brand_inject_all_quick_edit_data()
{
    $screen = get_current_screen();
    $taxonomy_slug = '_themename_product_brand';
    $post_type_slug = '_themename_product';

    // Only run on the correct post type list table.
    if (!is_admin() || $screen->base !== 'edit' || $screen->post_type !== $post_type_slug) {
        return;
    }

    $posts = get_posts(array(
        'post_type'      => $post_type_slug,
        'posts_per_page' => -1, // Retrieve all posts on the current page.
        'fields'         => 'ids',
        'paged'          => 1,
        // CRITICAL: Use the same query arguments as the main post list query
        'post_status'    => array('publish', 'pending', 'draft', 'future', 'private', 'trash'),
        'suppress_filters' => false,
    ));

    $brand_data = array();

    foreach ($posts as $post_id) {
        $terms = wp_get_post_terms($post_id, $taxonomy_slug, array('fields' => 'ids'));
        $term_id = !is_wp_error($terms) && !empty($terms) ? absint($terms[0]) : 0;

        // Map Post ID to Brand ID
        $brand_data[$post_id] = $term_id;
    }

    // Inject the JSON object into the footer
    if (!empty($brand_data)) {
        echo '<script type="text/javascript">';
        echo 'var _themename_product_brand_data = ' . json_encode($brand_data) . ';';
        echo '</script>';
    }
}
// This hook runs in the footer, after the post loop has completed.
add_action('admin_footer-edit.php', '_themename_product_brand_inject_all_quick_edit_data', 9);

/**
 * JavaScript to read the brand ID from the global data object and select the option.
 */
/**
 * JavaScript to hide the default tag input and set the custom dropdown value.
 */
function _themename_product_brand_quick_edit_js()
{
    $taxonomy_slug = '_themename_product_brand';
    $post_type_slug = '_themename_product';

    if (get_current_screen()->post_type !== $post_type_slug) {
        return;
    }

?>
    <script type="text/javascript">
        jQuery(function($) {
            var taxonomy = '<?php echo esc_js($taxonomy_slug); ?>';
            var default_tax_selector = 'textarea[data-wp-taxonomy="' + taxonomy + '"]';

            // On Quick Edit click
            $('#the-list').on('click', '.editinline', function() {
                var $this = $(this),
                    $tr = $this.closest('tr'),
                    $editTr = $('#edit-' + $tr.attr('id').replace('post-', '')),

                    // --- Custom Logic for Dropdown Selection (from previous answers) ---
                    postId = $tr.attr('id').replace('post-', ''),
                    $select = $editTr.find('select.post_brand_select'),
                    $hiddenIdElement = $('#brand-data-' + postId);

                // 1. CRITICAL: Hide the default WordPress tag input wrapper
                $editTr.find(default_tax_selector).closest('.inline-edit-tags-wrap').hide();

                // 2. Set the custom dropdown value (using the reliable global data)
                if (typeof _themename_product_brand_data !== 'undefined') {
                    var currentTermId = _themename_product_brand_data[postId];
                    var selectedId = (currentTermId && currentTermId > 0) ? currentTermId.toString() : '-1';
                    $select.val(selectedId);
                }
            });
        });
    </script>
<?php
}
// Ensure this hook runs AFTER the global data injection script
add_action('admin_footer-edit.php', '_themename_product_brand_quick_edit_js', 10);

/**
 * Save the single Brand term when the Quick Edit is submitted.
 */
function _themename_save_quick_edit_product_brand($post_id)
{
    $taxonomy_slug = '_themename_product_brand';
    $input_name = $taxonomy_slug . '_quick_edit_select';

    // 1. Check for quick edit context and permissions
    if (!isset($_POST['_inline_edit'])) {
        return;
    }
    if (!current_user_can('edit_post', $post_id) || wp_is_post_revision($post_id)) {
        return;
    }

    // 2. Sanitize and validate the input
    if (isset($_POST[$input_name])) {
        $term_id = absint($_POST[$input_name]);

        if ($term_id > 0) {
            // Assign the single selected term
            // The third argument (true) means to append/replace, but since it's an array 
            // of one ID, it effectively replaces.
            wp_set_post_terms($post_id, array($term_id), $taxonomy_slug);
        } else {
            // If '-1' (No Brand) is selected, remove all terms
            wp_set_post_terms($post_id, array(), $taxonomy_slug);
        }
    }
}
add_action('save_post', '_themename_save_quick_edit_product_brand');
