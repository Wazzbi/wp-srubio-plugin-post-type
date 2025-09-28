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
        'name'                       => esc_html_x('Brands', 'Taxonomy General Name', '_themename-_pluginname'),
        'singular_name'              => esc_html_x('Brand', 'Taxonomy Singular Name', '_themename-_pluginname'),
        'menu_name'                  => esc_html__('Brands', '_themename-_pluginname'),
        'all_items'                  => esc_html__('All Brands', '_themename-_pluginname'),
        'edit_item'                  => esc_html__('Edit Brand', '_themename-_pluginname'),
        'view_item'                  => esc_html__('View Brand', '_themename-_pluginname'),
        'update_item'                => esc_html__('Update Brand', '_themename-_pluginname'),
        'add_new_item'               => esc_html__('Add New Brand', '_themename-_pluginname'),
        'new_item_name'              => esc_html__('New Brand Name', '_themename-_pluginname'),
        'parent_item'                => null,
        'parent_item_colon'          => null,
        'search_items'               => esc_html__('Search Brands', '_themename-_pluginname'),
        'popular_items'              => esc_html__('Popular Brands', '_themename-_pluginname'),
        'separate_items_with_commas' => esc_html__('Separate brands with commas', '_themename-_pluginname'),
        'add_or_remove_items'        => esc_html__('Add or remove brands', '_themename-_pluginname'),
        'choose_from_most_used'      => esc_html__('Choose from the most used brands', '_themename-_pluginname'),
        'not_found'                  => esc_html__('No Brands Found', '_themename-_pluginname'),
        'no_terms'                   => esc_html__('No brands', '_themename-_pluginname'),
        'items_list'                 => esc_html__('Brands list', '_themename-_pluginname'),
        'items_list_navigation'      => esc_html__('Brands list navigation', '_themename-_pluginname'),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => false,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => false,
        'rewrite'                    => array('slug' => 'brand', 'hierarchical' => false),
        'meta_box_cb'                => false,
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
 */
function _themename_product_brand_metabox_callback($post)
{
    $taxonomy_slug = '_themename_product_brand';
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
                                name="tax_input[<?php echo esc_attr($taxonomy_slug); ?>]"
                                id="in-<?php echo esc_attr($taxonomy_slug); ?>-<?php echo esc_attr($term->term_id); ?>"
                                <?php checked($selected_id, $term->term_id); ?> />
                            <?php echo esc_html($term->name); ?>
                        </label>
                    </li>
                <?php endforeach; ?>
                <li>
                    <label class="selectit">
                        <input value="0" type="radio"
                            name="tax_input[<?php echo esc_attr($taxonomy_slug); ?>]"
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

                            var newListItem = '<li id="term-' + newTermId + '">' +
                                '<label class="selectit">' +
                                '<input value="' + newTermId + '" type="radio" name="tax_input[' + taxonomy + ']" ' +
                                'id="in-' + taxonomy + '-' + newTermId + '" checked="checked" /> ' +
                                newTermName + '</label></li>';

                            checklist.append(newListItem);

                            checklist.find('input[type="radio"]').not('#in-' + taxonomy + '-' + newTermId).prop('checked', false);

                            // 🚨 CORRECTED RESET LOGIC: Target the image fields using the form container
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
 */
function _themename_save_product_brand_metabox($post_id)
{
    // ... (This function remains unchanged as it handles post-save, not term-create) ...
    $taxonomy_slug = '_themename_product_brand';

    if (!current_user_can('edit_post', $post_id) || wp_is_post_revision($post_id)) {
        return;
    }

    if (!isset($_POST[$taxonomy_slug . '_nonce_field']) || !wp_verify_nonce($_POST[$taxonomy_slug . '_nonce_field'], $taxonomy_slug . '_nonce')) {
        return;
    }

    if (isset($_POST['tax_input'][$taxonomy_slug])) {
        $term_id = absint($_POST['tax_input'][$taxonomy_slug]);

        if ($term_id > 0) {
            wp_set_post_terms($post_id, array($term_id), $taxonomy_slug, false);
        } else {
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


// // -----------------------------------------------------------------------------
// // C. SCRIPTS (MUST LOAD MEDIA FRAMEWORK FOR AJAX)
// // -----------------------------------------------------------------------------

// /* Enqueue media script on product post edit screen. */
// function _themename_enqueue_admin_media_globally($hook)
// {
//     // Check if we are on a Product (or new Product) editing page
//     if ('post.php' === $hook || 'post-new.php' === $hook) {
//         $post_type = get_post_type();
        
//         if ( '_themename_product' === $post_type ) {
//             // CRITICAL: We need wp_enqueue_media() to load the media frame logic
//             // so the JS inside the metabox works.
//             wp_enqueue_media();
//         }
//     }
//     // Also keep the original hooks for the term edit pages
//     $allowed_screens = ['edit-tags.php', 'term.php'];
//     if (in_array($hook, $allowed_screens) && isset($_GET['taxonomy']) && in_array($_GET['taxonomy'], ['_themename_product_category', '_themename_product_brand'])) {
//         wp_enqueue_media();
//         // Since the JS for the media buttons on the main term page is the same, we re-enqueue it here.
//         wp_enqueue_script('custom-taxonomy-image', plugin_dir_url(__FILE__) . '../dist/assets/js/taxonomy-image.js', array('jquery'), null, true);
//     }
// }
// add_action('admin_enqueue_scripts', '_themename_enqueue_admin_media_globally');