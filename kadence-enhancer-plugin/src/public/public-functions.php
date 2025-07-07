<?php
/**
 * Public Functions for Kadence Enhancer Plugin
 *
 * This file contains functions that enhance the public-facing side of the website.
 */

// Enqueue front-end scripts and styles for Kadence theme integration
function kep_enqueue_public_scripts() {
    if (is_admin()) return;
    
    // Only load if shortcode is present
    global $post;
    if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'kadence_youtube_carousel')) {
        wp_enqueue_style('kep-youtube-style', plugin_dir_url(__FILE__) . 'css/youtube-carousel.css', array(), '1.1', 'all');
    }
}
add_action('wp_enqueue_scripts', 'kep_enqueue_public_scripts');

// Kadence theme specific enhancements
function kep_kadence_theme_support() {
    if (function_exists('kadence')) {
        // Add theme-specific customizations here
        add_filter('kadence_theme_options', 'kep_modify_kadence_options');
    }
}
add_action('after_setup_theme', 'kep_kadence_theme_support');

function kep_modify_kadence_options($options) {
    // Customize Kadence theme options if needed
    return $options;
}

// Widget support for YouTube carousel
function kep_register_youtube_widget() {
    register_widget('KEP_YouTube_Widget');
}
add_action('widgets_init', 'kep_register_youtube_widget');

class KEP_YouTube_Widget extends WP_Widget {
    function __construct() {
        parent::__construct('kep_youtube_widget', 'Relaxed Axolotl YouTube', array('description' => 'Muestra el carrusel de YouTube de Relaxed Axolotl'));
    }
    
    function widget($args, $instance) {
        echo $args['before_widget'];
        echo do_shortcode('[kadence_youtube_carousel]');
        echo $args['after_widget'];
    }
    
    function form($instance) {
        echo '<p>Este widget muestra automáticamente el carrusel de YouTube configurado en el panel de administración.</p>';
    }
}
?>