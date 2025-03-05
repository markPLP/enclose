<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

require get_theme_file_path('/inc/custom-mega-menu-api.php');
//require get_theme_file_path('/inc/custom-mega-menu-products.php');

// Load parent and child theme styles
function hello_elementor_child_enqueue_assets() {
    // Enqueue parent theme styles
    wp_enqueue_style('hello-elementor-parent', get_template_directory_uri() . '/style.css');
    // Enqueue child theme styles
    wp_enqueue_style('hello-elementor-child', get_stylesheet_directory_uri() . '/style.css', array(
        'hello-elementor-parent'), 
        wp_get_theme()->get('Version')
    );

    // Enqueue JavaScript file with module type
    $script_path = get_stylesheet_directory() . '/src/index.js';
    if (file_exists($script_path)) { // Ensure file exists before enqueuing
        wp_enqueue_script(
            'custom-mega-menu', 
            get_stylesheet_directory_uri() . '/src/index.js', 
            array('jquery'), 
            null, 
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'hello_elementor_child_enqueue_assets');

// Add type="module" to the script tag
function add_module_attribute($tag, $handle, $src) {
    if ('custom-mega-menu' === $handle) {
        $tag = str_replace(' src', ' type="module" src', $tag);
    }
    return $tag;
}
add_filter('script_loader_tag', 'add_module_attribute', 10, 3);

/**
* header widget area 1
 */

function custom_header_widget_one() {
    $args = array(
      'id' 							=> 'header-widget-col-one',
      'name'						=> __('Header Column One', 'text_domain'),
      'description'			=> __('Column One', 'text_domain'),
      'before_title'		=> '<h3 class="title">',
      'after_title' 		=> '</h3>',
      'before_widget'		=> '<div id="%1$s" class="widget %2$s">',
      'after_widget'    => '</div>'
    );
    register_sidebar($args);
}
add_action('widgets_init', 'custom_header_widget_one');

/**
* header widget area 1
 */

function custom_header_widget_two() {
    $args = array(
      'id' 							=> 'header-widget-col-two',
      'name'						=> __('Header Column Two', 'text_domain'),
      'description'			=> __('Column Two', 'text_domain'),
      'before_title'		=> '<h3 class="title">',
      'after_title' 		=> '</h3>',
      'before_widget'		=> '<div id="%1$s" class="widget %2$s">',
      'after_widget'    => '</div>'
    );
    register_sidebar($args);
}
add_action('widgets_init', 'custom_header_widget_two');

/**
* header widget area 1
 */

function custom_header_widget_three() {
    $args = array(
      'id' 							=> 'header-widget-col-three',
      'name'						=> __('Header Column Three', 'text_domain'),
      'description'			=> __('Column Three', 'text_domain'),
      'before_title'		=> '<h3 class="title">',
      'after_title' 		=> '</h3>',
      'before_widget'		=> '<div id="%1$s" class="widget %2$s">',
      'after_widget'    => '</div>'
    );
    register_sidebar($args);
}
add_action('widgets_init', 'custom_header_widget_three');