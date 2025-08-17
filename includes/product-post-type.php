<?php
function _themename__pluginname_setup_post_type()
{
    $labels = array(
        'name' => esc_html_x('Products', 'Post type general name', '_themename-_pluginname'),
        'singular_name' => esc_html_x('Product', 'Post type singular name', '_themename-_pluginname'),
        'menu_name' => esc_html_x('Products', 'Admin Menu text', '_themename-_pluginname'),
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
        'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
        'rewrite' => array('slug' => 'products')
    ));
};
add_action('init', '_themename__pluginname_setup_post_type');
