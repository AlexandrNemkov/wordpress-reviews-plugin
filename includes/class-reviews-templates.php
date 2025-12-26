<?php
/**
 * Template handling for Reviews
 */

if (!defined('ABSPATH')) {
    exit;
}

class Reviews_Templates {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_filter('template_include', array($this, 'template_loader'));
        add_filter('archive_template', array($this, 'archive_template'));
        add_filter('single_template', array($this, 'single_template'));
        add_action('pre_get_posts', array($this, 'modify_archive_query'));
    }
    
    public function modify_archive_query($query) {
        // Only modify main query on frontend
        if (!is_admin() && $query->is_main_query()) {
            // Check if this is the reviews archive page
            if (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/reviews') !== false && !is_singular()) {
                $query->set('post_type', 'review');
                $query->set('post_status', 'publish');
                $query->set('posts_per_page', 24);
            }
        }
    }
    
    public function template_loader($template) {
        // Check if this is a review archive by multiple methods
        $is_review_archive = is_post_type_archive('review') || 
                            (is_archive() && get_query_var('post_type') === 'review') ||
                            (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/reviews') !== false && !is_singular());
        
        if ($is_review_archive) {
            $archive_template = $this->locate_template('archive-review.php');
            if ($archive_template) {
                return $archive_template;
            }
        }
        
        if (is_singular('review')) {
            $single_template = $this->locate_template('single-review.php');
            if ($single_template) {
                return $single_template;
            }
        }
        
        return $template;
    }
    
    public function archive_template($template) {
        if (is_post_type_archive('review')) {
            $archive_template = $this->locate_template('archive-review.php');
            if ($archive_template) {
                return $archive_template;
            }
        }
        return $template;
    }
    
    public function single_template($template) {
        if (is_singular('review')) {
            $single_template = $this->locate_template('single-review.php');
            if ($single_template) {
                return $single_template;
            }
        }
        return $template;
    }
    
    private function locate_template($template_name) {
        // Check theme first
        $theme_template = locate_template(array('reviews/' . $template_name));
        if ($theme_template) {
            return $theme_template;
        }
        
        // Check plugin templates
        $plugin_template = REVIEWS_PLUGIN_DIR . 'templates/' . $template_name;
        if (file_exists($plugin_template)) {
            return $plugin_template;
        }
        
        return false;
    }
}

