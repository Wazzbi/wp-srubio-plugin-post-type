<?php
function _themename__pluginname_register_skills_tax()
{
    $labels = array(
        'name' => esc_html_x('Skills', 'taxonomy general name', '__themename-pluginname'),
        'singular_name' => esc_html_x('Skill', 'taxonomy singular name', '__themename-pluginname'),
        'search_items' => esc_html__('Search Skills', '__themename-pluginname'),
        'all_items' => esc_html__('All Skills', '__themename-pluginname'),
        'parent_item' => esc_html__('Parent Skill', '__themename-pluginname'),
        'parent_item_colon' => esc_html__('Parent Skill:', '__themename-pluginname'),
        'edit_item' => esc_html__('Edit Skill', '__themename-pluginname'),
        'update_item' => esc_html__('Update Skill', '__themename-pluginname'),
        'add_new_item' => esc_html__('Add New Skill', '__themename-pluginname'),
        'new_item_name' => esc_html__('New Skill Name', '__themename-pluginname'),
        'menu_name' => esc_html__('Skills', '__themename-pluginname'),
    );
    $args = array(
        'hierarchical' => false,
        'show_admin_column' => true,
        'labels' => $labels,
        'rewrite' => ['slug' => 'skills']
    );
    register_taxonomy('_themename_skills', ['_themename_portfolio'], $args);
};

add_action('init', '_themename__pluginname_register_skills_tax');
