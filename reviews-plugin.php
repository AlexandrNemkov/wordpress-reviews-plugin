<?php
/**
 * Plugin Name: Reviews Management Plugin
 * Plugin URI: https://example.com
 * Description: Плагин для управления отзывами с галереей фотографий и фильтрацией
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://example.com
 * Text Domain: reviews-plugin
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Define plugin constants
define('REVIEWS_PLUGIN_VERSION', '1.0.0');
define('REVIEWS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('REVIEWS_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once REVIEWS_PLUGIN_DIR . 'includes/class-reviews-post-type.php';
require_once REVIEWS_PLUGIN_DIR . 'includes/class-reviews-admin.php';
require_once REVIEWS_PLUGIN_DIR . 'includes/class-reviews-templates.php';
require_once REVIEWS_PLUGIN_DIR . 'includes/class-reviews-ajax.php';

// Initialize plugin
class Reviews_Plugin {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->init_hooks();
    }
    
    private function init_hooks() {
        // Initialize post type
        Reviews_Post_Type::get_instance();
        
        // Initialize admin
        if (is_admin()) {
            Reviews_Admin::get_instance();
        }
        
        // Initialize templates
        Reviews_Templates::get_instance();
        
        // Initialize AJAX
        Reviews_Ajax::get_instance();
        
        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }
    
    public function enqueue_scripts() {
        wp_enqueue_style(
            'reviews-plugin-style',
            REVIEWS_PLUGIN_URL . 'assets/css/reviews-style.css',
            array(),
            REVIEWS_PLUGIN_VERSION
        );
        
        wp_enqueue_script(
            'reviews-plugin-script',
            REVIEWS_PLUGIN_URL . 'assets/js/reviews-script.js',
            array('jquery'),
            REVIEWS_PLUGIN_VERSION,
            true
        );
        
        wp_localize_script('reviews-plugin-script', 'reviewsAjax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('reviews_nonce')
        ));
    }
}

// Initialize the plugin
Reviews_Plugin::get_instance();

