<?php
/*
Plugin Name: _themename _pluginname
Plugin URI:
Description: Adding Custom Post Types for _themename
Version: 1.0.0
Author: David Novotny
Author URI:
License:
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: _themename-_pluginname
Domain Path: /languages
*/

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

include_once('includes/portfolio-post-type.php');
include_once('includes/project-type-tax.php');
include_once('includes/skills-tax.php');

function _themename__pluginname_activate()
{
    _themename__pluginname_setup_post_type();
    _themename__pluginname_register_project_type_tax();
    _themename__pluginname_register_skills_tax();
    flush_rewrite_rules();
}

register_activation_hook(__FILE__, '_themename__pluginname_activate');

function _themename__pluginname_deactivate()
{
    unregister_post_type('_themename_portfolio');
    unregister_taxonomy('_themename_project_type', '_themename_skills');
    flush_rewrite_rules();
}

register_activation_hook(__FILE__, '_themename__pluginname_deactivate');

function _themename__pluginname_templates($template)
{
    if (is_singular('_themename_portfolio')) {
        $file_name = 'single-_themename_portfolio.php';
        $theme_template = locate_template($file_name);

        // If the theme has a custom template, use it
        if ($theme_template) {
            return $theme_template;
        } else {
            // Otherwise, use the plugin's default template
            $plugin_template = plugin_dir_path(__FILE__) . 'includes/templates/' . $file_name;
            if (file_exists($plugin_template)) {
                return $plugin_template;
            }
        }
    }

    if (is_post_type_archive('_themename_portfolio')) {
        $file_name = 'archive-_themename_portfolio.php';
        $theme_template = locate_template($file_name);

        if ($theme_template) {
            return $theme_template;
        } else {
            $plugin_template = plugin_dir_path(__FILE__) . 'includes/templates/' . $file_name;
            if (file_exists($plugin_template)) {
                return $plugin_template;
            }
        }
    }

    if (is_tax('_themename_project_type')) {
        $file_name = 'taxonomy-_themename_project_type.php';
        $theme_template = locate_template($file_name);

        if ($theme_template) {
            return $theme_template;
        } else {
            $plugin_template = plugin_dir_path(__FILE__) . 'includes/templates/' . $file_name;
            if (file_exists($plugin_template)) {
                return $plugin_template;
            }
        }
    }

    if (is_tax('_themename_skills')) {
        $file_name = 'taxonomy-_themename_skills.php';
        $theme_template = locate_template($file_name);

        if ($theme_template) {
            return $theme_template;
        } else {
            $plugin_template = plugin_dir_path(__FILE__) . 'includes/templates/' . $file_name;
            if (file_exists($plugin_template)) {
                return $plugin_template;
            }
        }
    }

    return $template;
}

add_filter('template_include', '_themename__pluginname_templates');
