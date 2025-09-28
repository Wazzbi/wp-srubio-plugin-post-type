<?php

/**
 * ===========================================
 * Enqueue Scripts for Admin UI
 * ===========================================
 */
function _themename_enqueue_taxonomy_scripts($hook)
{
    // Check if we are on the taxonomy edit screen or post edit screen
    if ('edit-tags.php' !== $hook && 'term.php' !== $hook) {
        return;
    }

    // Check if the current taxonomy is either the category or the brand
    if (isset($_GET['taxonomy']) && in_array($_GET['taxonomy'], ['_themename_product_category', '_themename_product_brand'])) {
        wp_enqueue_media();
        wp_enqueue_script('custom-taxonomy-image', plugin_dir_url(__FILE__) . '../dist/assets/js/taxonomy-image.js', array('jquery'), null, true);
    }
}

add_action('admin_enqueue_scripts', '_themename_enqueue_taxonomy_scripts');
