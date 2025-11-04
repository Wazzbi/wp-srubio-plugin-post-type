<?php

/**
 * Utility function to retrieve the File ID or URL from the CPT Settings.
 *
 * @param string $field_key The specific key for the file field
 * ('_themename_post_types_field_file_catalog' or
 * '_themename_post_types_field_file_price_list').
 * @param string $return_type What to return: 'id' or 'url'. Default is 'url'.
 * @return string|int|false The file URL (string), file ID (int), or false if not found.
 */
function _themename_post_types_get_product_file_data($field_key, $return_type = 'url')
{
    // 1. Retrieve the entire options array from the database.
    $options = get_option('_themename__pluginname_product_options');

    // 2. Check if the options array exists and if the specific key is set.
    if (! is_array($options) || ! isset($options[$field_key])) {
        return false;
    }

    // 3. Get and sanitize the saved Attachment ID.
    $attachment_id = absint($options[$field_key]);

    // If the ID is 0 (not set), return false.
    if ($attachment_id === 0) {
        return false;
    }

    // 4. Determine the return type.
    if ($return_type === 'id') {
        return $attachment_id;
    }

    // Default return is 'url'. Use the ID to get the file URL.
    $file_url = wp_get_attachment_url($attachment_id);

    if ($file_url) {
        return esc_url($file_url);
    }

    return false;
}
// How to Use:
// $catalog_url = _themename_post_types_get_product_file_data( '_themename_post_types_field_file_catalog', 'url' );
// $price_list_id = _themename_post_types_get_product_file_data( '_themename_post_types_field_file_price_list', 'id' );



/**
 * Utility function to retrieve the Global Product Content from the Rich Text Editor.
 *
 * This function retrieves the saved content and applies wpautop()
 * to convert line breaks into proper HTML paragraphs.
 *
 * @return string|false The formatted HTML content (string), or false if not found.
 */
function _themename_post_types_get_global_product_content()
{
    // 1. Define the key used to save the editor content.
    $field_key = '_themename__pluginname_field_editor_content';

    // 2. Retrieve the entire options array.
    $options = get_option('_themename__pluginname_product_options');

    // 3. Check if the options array exists and if the specific key is set.
    if (! is_array($options) || ! isset($options[$field_key])) {
        return false;
    }

    // 4. Get the raw content. It was already sanitized using wp_kses_post() when saved.
    $raw_content = $options[$field_key];

    // Check if the content is empty.
    if (empty($raw_content)) {
        return false;
    }

    // 5. CRITICAL STEP: Apply wpautop() to ensure paragraphs are correctly formatted.
    $formatted_content = wpautop($raw_content);

    return $formatted_content;
}
// How to Use:
// IMPORTANT: The content is already HTML formatted and escaped (sanitized) when saved.
// $global_content = _themename_post_types_get_global_product_content();