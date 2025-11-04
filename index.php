<?php
/*
Plugin Name: _themename _pluginname
Plugin URI:
Description: Adding Custom Post Types for _themename
Version: 1.2
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

include_once('includes/product-post-type.php');
// include_once('includes/product-category-tax.php');
// include_once('includes/product-brand-tax.php');
include_once('includes/enqueue-assets.php');
include_once('includes/utility-functions.php');


function _themename__pluginname_activate()
{
    _themename__pluginname_setup_post_type();
    // _themename__pluginname_register_product_category_tax();
    // _themename__pluginname_register_product_brand_tax();
    flush_rewrite_rules();
}

register_activation_hook(__FILE__, '_themename__pluginname_activate');

function _themename__pluginname_deactivate()
{
    unregister_post_type('_themename_product');
    // unregister_taxonomy('_themename_product_category');
    // unregister_taxonomy('_themename_product_brand');
    flush_rewrite_rules();
}

register_activation_hook(__FILE__, '_themename__pluginname_deactivate');
