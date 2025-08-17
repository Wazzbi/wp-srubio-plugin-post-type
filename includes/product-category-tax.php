<?php
function _themename__pluginname_register_product_category_tax()
{
    $labels = array(
        'name'                       => esc_html_x('Categories', 'Taxonomy General Name', '_themename-_pluginname'),
        'singular_name'              => esc_html_x('Category', 'Taxonomy Singular Name', '_themename-_pluginname'),
        'menu_name'                  => esc_html__('Categories', '_themename-_pluginname'),
        'all_items'                  => esc_html__('All Categories', '_themename-_pluginname'),
        'parent_item'                => esc_html__('Parent Category', '_themename-_pluginname'),
        'parent_item_colon'          => esc_html__('Parent Category:', '_themename-_pluginname'),
        'new_item_name'              => esc_html__('New Category Name', '_themename-_pluginname'),
        'add_new_item'               => esc_html__('Add New Category', '_themename-_pluginname'),
        'edit_item'                  => esc_html__('Edit Category', '_themename-_pluginname'),
        'update_item'                => esc_html__('Update Category', '_themename-_pluginname'),
        'view_item'                  => esc_html__('View Category', '_themename-_pluginname'),
        'separate_items_with_commas' => esc_html__('Separate categories with commas', '_themename-_pluginname'),
        'add_or_remove_items'        => esc_html__('Add or remove categories', '_themename-_pluginname'),
        'choose_from_most_used'      => esc_html__('Choose from the most used', '_themename-_pluginname'),
        'popular_items'              => esc_html__('Popular Categories', '_themename-_pluginname'),
        'search_items'               => esc_html__('Search Categories', '_themename-_pluginname'),
        'not_found'                  => esc_html__('Not Found', '_themename-_pluginname'),
        'no_terms'                   => esc_html__('No categories', '_themename-_pluginname'),
        'items_list'                 => esc_html__('Categories list', '_themename-_pluginname'),
        'items_list_navigation'      => esc_html__('Categories list navigation', '_themename-_pluginname'),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'rewrite' => array('slug' => 'catalog')
    );
    register_taxonomy('_themename_product_category', ['_themename_product'], $args);
};

add_action('init', '_themename__pluginname_register_product_category_tax');



// ----- The fix for hierarchy display in the admin UI (items depth 2+ shown as depth 0)  ----- //

/**
 * Remove the default flat taxonomy metabox and add our custom hierarchical one.
 * This function is hooked to the `add_meta_boxes` action.
 */
function _themename_custom_taxonomy_metabox()
{
    // The default meta box for a hierarchical taxonomy is named after the taxonomy slug, followed by 'div'.
    // We remove it from our custom post type.
    remove_meta_box('_themename_product_categorydiv', '_themename_product', 'side');

    // Add a new meta box with a custom callback function.
    add_meta_box(
        '_themename_product_category_metabox', // Unique ID for the meta box
        esc_html__('Product Categories', '_themename-_pluginname'), // Title
        '_themename_product_category_metabox_callback', // The callback function to display the content
        '_themename_product', // The custom post type where this meta box will appear
        'side', // Context (side, normal, or advanced)
        'core' // Priority
    );
}

// Hook our function to the 'add_meta_boxes' action.
add_action('add_meta_boxes', '_themename_custom_taxonomy_metabox');

/**
 * The callback function to display the hierarchical taxonomy meta box.
 * This is where we create the UI that correctly shows the parent-child relationships.
 */
function _themename_product_category_metabox_callback($post)
{
    // Get the terms associated with the current post.
    $post_terms = get_terms([
        'object_ids' => $post->ID,
        'taxonomy'   => '_themename_product_category',
        'fields'     => 'ids',
    ]);
    if (!is_wp_error($post_terms)) {
        $post_terms_ids = array_values($post_terms);
    } else {
        $post_terms_ids = array();
    }

    // Get all terms from the taxonomy. We will build the hierarchy ourselves.
    $all_terms = get_terms([
        'taxonomy'   => '_themename_product_category',
        'hide_empty' => false,
    ]);

    // Build a hierarchical tree of terms.
    $term_tree = _themename_build_term_tree($all_terms);

?>
    <div id="taxonomy-<?php echo esc_attr('_themename_product_category'); ?>" class="categorydiv">
        <!-- The tab system for All Categories and Most Used, which is required for the Add New functionality to work -->
        <ul id="<?php echo esc_attr('_themename_product_category'); ?>-tabs" class="category-tabs">
            <li class="tabs"><a href="#<?php echo esc_attr('_themename_product_category'); ?>-all"><?php esc_html_e('All Categories', '_themename-_pluginname'); ?></a></li>
        </ul>

        <!-- All Categories Tab -->
        <div id="<?php echo esc_attr('_themename_product_category'); ?>-all" class="tabs-panel">
            <ul id="<?php echo esc_attr('_themename_product_category'); ?>checklist" class="categorychecklist form-no-clear">
                <?php _themename_render_term_tree($term_tree, $post_terms_ids, 0); ?>
            </ul>
        </div>



        <!-- Add New Category Form -->
        <div id="<?php echo esc_attr('_themename_product_category'); ?>-adder" class="wp-hidden-no-js category-adder">
            <h4>
                <a id="<?php echo esc_attr('_themename_product_category'); ?>-add-toggle" href="#<?php echo esc_attr('_themename_product_category'); ?>-add" class="hide-if-no-js taxonomy-add-new">
                    <?php esc_html_e('+ Add New Category', '_themename-_pluginname'); ?>
                </a>
            </h4>
            <p id="<?php echo esc_attr('_themename_product_category'); ?>-add" class="category-add" style="display: none;">
                <label class="screen-reader-text" for="new_<?php echo esc_attr('_themename_product_category'); ?>"><?php esc_html_e('Add New Category', '_themename-_pluginname'); ?></label>
                <input type="text" name="new_<?php echo esc_attr('_themename_product_category'); ?>" id="new_<?php echo esc_attr('_themename_product_category'); ?>" class="form-required form-input-tip" value="" aria-required="true" />
                <label class="screen-reader-text" for="new_<?php echo esc_attr('_themename_product_category'); ?>_parent"><?php esc_html_e('Parent Category', '_themename-_pluginname'); ?></label>
                <?php
                // Dropdown to select a parent term.
                wp_dropdown_categories(array(
                    'taxonomy'         => '_themename_product_category',
                    'hide_empty'       => 0,
                    'name'             => 'new_parent_id',
                    'id'               => 'new_parent_id',
                    'orderby'          => 'name',
                    'hierarchical'     => 1,
                    'show_option_none' => '&mdash; ' . esc_html__('Parent Category', '_themename-_pluginname') . ' &mdash;',
                ));
                ?>
                <input type="button" id="<?php echo esc_attr('_themename_product_category'); ?>-add-submit" class="button category-add-submit" value="<?php esc_html_e('Add New Category', '_themename-_pluginname'); ?>">
                <?php wp_nonce_field('add-taxonomy', '_ajax_nonce', false); ?>
                <span id="ajax-response"></span>
            </p>
        </div>
    </div>
    <script>
        jQuery(document).ready(function($) {
            // Toggle the 'Add New Category' form
            $('#<?php echo esc_attr('_themename_product_category'); ?>-add-toggle').on('click', function(event) {
                event.preventDefault();
                $('#<?php echo esc_attr('_themename_product_category'); ?>-add').toggle();
                $('#new_<?php echo esc_attr('_themename_product_category'); ?>').focus();
            });

            // Handle the tab clicks
            $('#<?php echo esc_attr('_themename_product_category'); ?>-tabs a').on('click', function(event) {
                event.preventDefault();
                var target = $(this).attr('href');
                var tabs = $(this).closest('.category-tabs');
                var panels = tabs.siblings('.tabs-panel');

                // Toggle active tabs and panels
                tabs.find('li').removeClass('tabs');
                $(this).closest('li').addClass('tabs');
                panels.hide();
                $(target).show();
            });

            $('#<?php echo esc_attr('_themename_product_category'); ?>checklist').on('change', 'input[type="checkbox"]', function() {
                var $checkbox = $(this);

                // Check the parents if a child checkbox is checked
                if ($checkbox.is(':checked')) {
                    var $parentLi = $checkbox.closest('li').parent().closest('li');
                    while ($parentLi.length) {
                        var $parentCheckbox = $parentLi.find('input[type="checkbox"]:first');
                        $parentCheckbox.prop('checked', true);
                        $parentLi = $parentLi.parent().closest('li');
                    }
                }
            });

            $('#<?php echo esc_attr('_themename_product_category'); ?>checklist').on('change', 'input[type="checkbox"]', function() {
                var $checkbox = $(this);
                var term_id = $checkbox.val();
                var $li = $checkbox.closest('li');

                // Check the parents if a child checkbox is checked
                if ($checkbox.is(':checked')) {
                    var $parentLi = $li.parent().closest('li');
                    while ($parentLi.length) {
                        $parentLi.find('input[type="checkbox"]:first').prop('checked', true);
                        $parentLi = $parentLi.parent().closest('li');
                    }
                } else {
                    // Uncheck all children if a parent checkbox is unchecked
                    $li.find('ul input[type="checkbox"]').prop('checked', false);
                }
            });

            // Handle adding a new category via AJAX
            $('#<?php echo esc_attr('_themename_product_category'); ?>-add-submit').on('click', function(event) {
                event.preventDefault();

                var newTermInput = $('#new_<?php echo esc_attr('_themename_product_category'); ?>');
                var parentTermInput = $('#new_parent_id');
                var nonceInput = $('input[name="_ajax_nonce"]');
                var submitButton = $(this);
                var spinner = $('#ajax-response');

                // Basic validation
                if (newTermInput.val().trim() === '') {
                    newTermInput.focus();
                    return;
                }

                // Show a spinner and disable the button
                spinner.html('<div class="spinner"></div>');
                submitButton.prop('disabled', true);

                $.ajax({
                    url: ajaxurl, // WordPress global variable for the admin-ajax.php URL
                    type: 'POST',
                    data: {
                        action: 'add_product_category_ajax',
                        _ajax_nonce: nonceInput.val(),
                        taxonomy: '<?php echo esc_attr('_themename_product_category'); ?>',
                        term_name: newTermInput.val(),
                        parent_id: parentTermInput.val()
                    },
                    success: function(response) {
                        if (response.success) {
                            // Get the newly created term's data
                            var newTermId = response.data.term_id;
                            var newTermName = response.data.term_name;
                            var newTermParentId = parseInt(response.data.parent_id, 10);
                            var newTermDepth = 0; // We need to calculate this from the response if we were to render properly

                            // Find the depth of the parent term to calculate indentation
                            if (newTermParentId !== 0) {
                                var parentLi = $('#term-' + newTermParentId);
                                var parentDepth = parentLi.find('label').get(0).childNodes.length - 1;
                                newTermDepth = parentDepth + 1;
                            }

                            var newListItem = '<li id="term-' + newTermId + '">' +
                                '<label class="selectit">' +
                                '<input value="' + newTermId + '" type="checkbox" name="tax_input[<?php echo esc_attr('_themename_product_category'); ?>][]" ' +
                                'id="in-<?php echo esc_attr('_themename_product_category'); ?>-' + newTermId + '" checked="checked" /> ' +
                                newTermName + '</label></li>';

                            if (newTermParentId === 0) {
                                // Append to the main list if it's a top-level term
                                $('#<?php echo esc_attr('_themename_product_category'); ?>checklist').append(newListItem);
                            } else {
                                // Find the parent list item
                                var parentLi = $('#term-' + newTermParentId);
                                var parentUl = parentLi.find('ul:first');

                                // If a nested UL doesn't exist, create it
                                if (parentUl.length === 0) {
                                    parentUl = $('<ul class="children"></ul>');
                                    parentLi.append(parentUl);
                                }

                                // Append the new list item to the parent's UL
                                parentUl.append(newListItem);
                            }

                            // Add the highlight and fade effect
                            var newTermLi = $('#term-' + newTermId);
                            newTermLi.css('background-color', '#ffffa1ff'); // A light yellow similar to WordPress
                            newTermLi.animate({
                                'background-color': 'transparent'
                            }, 2000, function() {
                                $(this).css('background-color', ''); // Remove the inline style after animation
                            });

                            // Clear the form and reset the button
                            newTermInput.val('');
                            parentTermInput.val('-1');
                            submitButton.prop('disabled', false);
                            $('#new_<?php echo esc_attr('_themename_product_category'); ?>').focus();
                            spinner.html('');
                        } else {
                            // Display error message
                            spinner.html('<p class="error">' + response.data.message + '</p>');
                            submitButton.prop('disabled', false);
                        }
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
 * Helper function to build a hierarchical tree from a flat array of terms.
 *
 * @param array $flat_terms All terms from the taxonomy.
 * @param int   $parent_id  The parent term ID to start with.
 * @return array The hierarchical term tree.
 */
function _themename_build_term_tree($flat_terms, $parent_id = 0)
{
    $tree = [];
    foreach ($flat_terms as $term) {
        if ($term->parent == $parent_id) {
            $term->children = _themename_build_term_tree($flat_terms, $term->term_id);
            $tree[] = $term;
        }
    }
    return $tree;
}

/**
 * Helper function to recursively render the hierarchical term tree as a list of checkboxes.
 *
 * @param array $tree          The hierarchical term tree.
 * @param array $selected_ids  Array of term IDs currently assigned to the post.
 * @param int   $depth         Current depth of the tree (for indentation).
 */
function _themename_render_term_tree($tree, $selected_ids, $parent_id = 0)
{
    if (empty($tree)) {
        return;
    }

    foreach ($tree as $term) {
        $checked = in_array($term->term_id, $selected_ids) ? 'checked' : '';
    ?>
        <li id="term-<?php echo esc_attr($term->term_id); ?>" data-parent="<?php echo esc_attr($parent_id); ?>">
            <label class="selectit">
                <input value="<?php echo esc_attr($term->term_id); ?>" type="checkbox" name="tax_input[_themename_product_category][]" id="in-<?php echo esc_attr('_themename_product_category'); ?>-<?php echo esc_attr($term->term_id); ?>" <?php echo $checked; ?> />
                <?php echo esc_html($term->name); ?>
            </label>
    <?php
        if (!empty($term->children)) {
            echo '<ul>';
            _themename_render_term_tree($term->children, $selected_ids, $term->term_id);
            echo '</ul>';
        }
        echo '</li>';
    }
}

/**
 * AJAX handler to add a new term to the taxonomy.
 *
 * @return void
 */
function _themename_add_product_category_ajax()
{
    // Check if the user has the capability to create terms and verify the nonce.
    if (!current_user_can('manage_categories') || !check_ajax_referer('add-taxonomy', '_ajax_nonce', false)) {
        wp_send_json_error(array('message' => 'You do not have permission to do this.'));
    }

    $taxonomy = sanitize_text_field($_POST['taxonomy']);
    $term_name = sanitize_text_field($_POST['term_name']);
    $parent_id = intval($_POST['parent_id']);

    // Check if the taxonomy exists and the term name is not empty.
    if (!taxonomy_exists($taxonomy) || empty($term_name)) {
        wp_send_json_error(array('message' => 'Invalid data provided.'));
    }

    // Insert the new term.
    $result = wp_insert_term($term_name, $taxonomy, array('parent' => $parent_id));

    if (is_wp_error($result)) {
        wp_send_json_error(array('message' => $result->get_error_message()));
    } else {
        // Send a success response with the new term's data.
        wp_send_json_success(array(
            'term_id' => $result['term_id'],
            'term_name' => $term_name,
            'parent_id' => $parent_id,
        ));
    }
}

add_action('wp_ajax_add_product_category_ajax', '_themename_add_product_category_ajax');

/**
 * Save the terms from our custom taxonomy meta box.
 * This function is hooked to the `save_post` action.
 *
 * @param int $post_id The ID of the post being saved.
 */
function _themename_save_product_category_metabox($post_id)
{
    // Check if the current user has permission to save.
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Check if we are saving a revision. If so, our script should stop.
    if (wp_is_post_revision($post_id)) {
        return;
    }

    // Sanitize and get the term IDs from the form.
    // The name of our input is tax_input[_themename_product_category]
    $taxonomy_slug = '_themename_product_category';

    // Check if the tax_input array is set and not empty.
    if (isset($_POST['tax_input'][$taxonomy_slug])) {
        // Sanitize the input. Each term ID should be an integer.
        $term_ids = array_map('intval', $_POST['tax_input'][$taxonomy_slug]);

        // Use wp_set_post_terms() to update the post's terms.
        wp_set_post_terms($post_id, $term_ids, $taxonomy_slug);
    } else {
        // If no terms are checked, we need to remove all terms from the post for this taxonomy.
        // This handles the case where all checkboxes are unchecked.
        wp_set_post_terms($post_id, array(), $taxonomy_slug);
    }
}

// Hook our function to the `save_post` action.
add_action('save_post', '_themename_save_product_category_metabox');
