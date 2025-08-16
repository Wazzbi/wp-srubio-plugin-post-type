<?php
function _themename__pluginname_setup_post_type()
{
    $labels = array(
        'name' => esc_html_x('Portfolio', 'Post type general name', '__themename_pluginname'),
        'singular_name' => esc_html_x('Portfolio Item', 'Post type singular name', '__themename_pluginname'),
        'menu_name' => esc_html_x('Portfolio', 'Admin Menu text', '__themename_pluginname'),
        'name_admin_bar' => esc_html_x('Portfolio Item', 'Add New on Toolbar', '__themename_pluginname'),
        'add_new' => esc_html__('Add New', '__themename_pluginname'),
        'add_new_item' => esc_html__('Add New Portfolio Item', '__themename_pluginname'),
        'new_item' => esc_html__('New Portfolio Item', '__themename_pluginname'),
        'edit_item' => esc_html__('Edit Portfolio Item', '__themename_pluginname'),
        'view_item' => esc_html__('View Portfolio Item', '__themename_pluginname'),
        'view_items' => esc_html__('View Portfolio Items', '__themename_pluginname'),
        'all_items' => esc_html__('All Portfolio Items', '__themename_pluginname'),
        'search_items' => esc_html__('Search Portfolio Items', '__themename_pluginname'),
        'parent_item_colon' => esc_html__('Parent Portfolio Items:', '__themename_pluginname'),
        'not_found' => esc_html__('No Portfolio Items found.', '__themename_pluginname'),
        'not_found_in_trash' => esc_html__('No Portfolio Items found in Trash.', '__themename_pluginname'),
        'featured_image' => esc_html_x('Portfolio Item Image', 'Overrides the "Featured Image" phrase for this post type. Added in 4.3', '__themename_pluginname'),
        'set_featured_image' => esc_html_x('Set portfolio item image', 'Overrides the "Set featured image" phrase for this post type. Added in 4.3', '__themename_pluginname'),
        'remove_featured_image' => esc_html_x('Remove portfolio item image', 'Overrides the "Remove featured image" phrase for this post type. Added in 4.3', '__themename_pluginname'),
        'use_featured_image' => esc_html_x('Use as portfolio item image', 'Overrides the "Use as featured image" phrase for this post type. Added in 4.3', '__themename_pluginname'),
        'archives' => esc_html_x('Portfolio archives', 'The post type archive label used in nav menus. Default "Post Archives". Added in 4.4.', '__themename_pluginname'),
        'insert_into_item' => esc_html_x('Insert into portfolio item', 'Overrides the "Insert into post"/"Insert into page" phrase (used when inserting media into a post). Added in 4.4.', '__themename_pluginname'),
        'uploaded_to_this_item' => esc_html_x('Uploaded to this portfolio item', 'Overrides the "Uploaded to this post"/"Uploaded to this page" phrase (used when viewing media attached to a post). Added in 4.4.', '__themename_pluginname'),
        'filter_items_list' => esc_html_x('Filter portfolio items list', 'Screen reader text for the filter links heading on the post type listing screen. Default "Filter posts list"/"Filter pages list". Added in 4.4.', '__themename_pluginname'),
        'items_list_navigation' => esc_html_x('Portfolio items list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default "Posts list navigation"/"Pages list navigation". Added in 4.4.', '__themename_pluginname'),
        'items_list' => esc_html_x('Portfolio items list', 'Screen reader text for the items list heading on the post type listing screen. Default "Posts list"/"Pages list". Added in 4.4.', '__themename_pluginname'),
    );

    register_post_type('_themename_portfolio', array(
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-format-gallery',
        'labels' => $labels,
        'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
        'rewrite' => array('slug' => 'portfolio')
    ));
};
add_action('init', '_themename__pluginname_setup_post_type');
