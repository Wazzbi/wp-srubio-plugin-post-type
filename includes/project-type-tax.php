<?php
function _themename__pluginname_register_project_type_tax()
{
    $labels = array(
        'name' => esc_html_x('Project Type', 'taxonomy general name', '__themename-pluginname'),
        'singular_name' => esc_html_x('Project Type', 'taxonomy singular name', '__themename-pluginname'),
        'search_items' => esc_html__('Search Project Types', '__themename-pluginname'),
        'all_items' => esc_html__('All Project Types', '__themename-pluginname'),
        'parent_item' => esc_html__('Parent Project Type', '__themename-pluginname'),
        'parent_item_colon' => esc_html__('Parent Project Type:', '__themename-pluginname'),
        'edit_item' => esc_html__('Edit Project Type', '__themename-pluginname'),
        'update_item' => esc_html__('Update Project Type', '__themename-pluginname'),
        'add_new_item' => esc_html__('Add New Project Type', '__themename-pluginname'),
        'new_item_name' => esc_html__('New Project Type Name', '__themename-pluginname'),
        'menu_name' => esc_html__('Project Types', '__themename-pluginname')
    );
    $args = array(
        'hierarchical' => true,
        'show_admin_column' => true,
        'labels' => $labels,
        'rewrite' => array('slug' => 'project_type')
    );
    register_taxonomy('_themename_project_type', ['_themename_portfolio'], $args);
};

add_action('init', '_themename__pluginname_register_project_type_tax');
