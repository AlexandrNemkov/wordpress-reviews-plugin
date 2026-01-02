<?php
/**
 * AJAX functionality for Reviews
 */

if (!defined('ABSPATH')) {
    exit;
}

class Reviews_Ajax {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('wp_ajax_load_more_reviews', array($this, 'load_more_reviews'));
        add_action('wp_ajax_nopriv_load_more_reviews', array($this, 'load_more_reviews'));
        add_action('wp_ajax_filter_reviews', array($this, 'filter_reviews'));
        add_action('wp_ajax_nopriv_filter_reviews', array($this, 'filter_reviews'));
    }
    
    public function load_more_reviews() {
        check_ajax_referer('reviews_nonce', 'nonce');
        
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $posts_per_page = isset($_POST['posts_per_page']) ? intval($_POST['posts_per_page']) : 24;
        
        $args = array(
            'post_type' => 'review',
            'posts_per_page' => $posts_per_page,
            'paged' => $page,
            'post_status' => 'publish',
        );
        
        // Apply filters if provided
        if (isset($_POST['filters'])) {
            $filters = $_POST['filters'];
            
        if (!empty($filters['city'])) {
            // Cities are stored as meta fields, not taxonomy
            $city_slug = sanitize_text_field(urldecode($filters['city']));
            // Get all cities and find matching one by slug
            global $wpdb;
            $all_cities = $wpdb->get_col($wpdb->prepare(
                "SELECT DISTINCT meta_value FROM {$wpdb->postmeta} 
                WHERE meta_key = %s AND meta_value != ''",
                '_review_city'
            ));
            $city_name = '';
            foreach ($all_cities as $city) {
                // Compare decoded versions - sanitize_title returns encoded, so decode it
                if (urldecode(sanitize_title($city)) === $city_slug) {
                    $city_name = $city;
                    break;
                }
            }
                if ($city_name) {
                    if (!isset($args['meta_query'])) {
                        $args['meta_query'] = array();
                    }
                    $args['meta_query'][] = array(
                        'key' => '_review_city',
                        'value' => $city_name,
                        'compare' => '=',
                    );
                }
            }
            
            if (!empty($filters['product'])) {
                if (!isset($args['tax_query'])) {
                    $args['tax_query'] = array();
                }
                $args['tax_query'][] = array(
                    'taxonomy' => 'review_product',
                    'field' => 'slug',
                    'terms' => sanitize_text_field($filters['product']),
                );
            }
            
            if (!empty($filters['year'])) {
                // Years are stored as meta fields, not taxonomy
                $year_slug = sanitize_text_field(urldecode($filters['year']));
                // Get all years and find matching one by slug
                global $wpdb;
                $all_years = $wpdb->get_col($wpdb->prepare(
                    "SELECT DISTINCT meta_value FROM {$wpdb->postmeta} 
                    WHERE meta_key = %s AND meta_value != ''",
                    '_review_year'
                ));
                $year_name = '';
                foreach ($all_years as $year) {
                    // Compare decoded versions - sanitize_title returns encoded, so decode it
                    if (urldecode(sanitize_title($year)) === $year_slug) {
                        $year_name = $year;
                        break;
                    }
                }
                if ($year_name) {
                    if (!isset($args['meta_query'])) {
                        $args['meta_query'] = array();
                    }
                    $args['meta_query'][] = array(
                        'key' => '_review_year',
                        'value' => $year_name,
                        'compare' => '=',
                    );
                }
            }
            
            if (!empty($filters['has_video']) && $filters['has_video'] === 'true') {
                if (!isset($args['meta_query'])) {
                    $args['meta_query'] = array();
                }
                $args['meta_query'][] = array(
                    'key' => '_review_has_video',
                    'value' => '1',
                    'compare' => '=',
                );
            }
        }
        
        $query = new WP_Query($args);
        
        ob_start();
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $this->render_review_item();
            }
        }
        
        $html = ob_get_clean();
        
        wp_reset_postdata();
        
        wp_send_json_success(array(
            'html' => $html,
            'has_more' => $query->max_num_pages > $page,
        ));
    }
    
    public function filter_reviews() {
        check_ajax_referer('reviews_nonce', 'nonce');
        
        $filters = isset($_POST['filters']) ? $_POST['filters'] : array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $posts_per_page = isset($_POST['posts_per_page']) ? intval($_POST['posts_per_page']) : 24;
        
        $args = array(
            'post_type' => 'review',
            'posts_per_page' => $posts_per_page,
            'paged' => $page,
            'post_status' => 'publish',
        );
        
        $tax_query = array();
        
        if (!empty($filters['city'])) {
            // Cities are stored as meta fields, not taxonomy
            $city_slug = sanitize_text_field(urldecode($filters['city']));
            // Get all cities and find matching one by slug
            global $wpdb;
            $all_cities = $wpdb->get_col($wpdb->prepare(
                "SELECT DISTINCT meta_value FROM {$wpdb->postmeta} 
                WHERE meta_key = %s AND meta_value != ''",
                '_review_city'
            ));
            $city_name = '';
            foreach ($all_cities as $city) {
                // Compare decoded versions - sanitize_title returns encoded, so decode it
                if (urldecode(sanitize_title($city)) === $city_slug) {
                    $city_name = $city;
                    break;
                }
            }
            if ($city_name) {
                if (!isset($args['meta_query'])) {
                    $args['meta_query'] = array();
                }
                $args['meta_query'][] = array(
                    'key' => '_review_city',
                    'value' => $city_name,
                    'compare' => '=',
                );
            }
        }
        
        if (!empty($filters['product'])) {
            $tax_query[] = array(
                'taxonomy' => 'review_product',
                'field' => 'slug',
                'terms' => sanitize_text_field($filters['product']),
            );
        }
        
        if (!empty($filters['year'])) {
            // Years are stored as meta fields, not taxonomy
            $year_slug = sanitize_text_field(urldecode($filters['year']));
            // Get all years and find matching one by slug
            global $wpdb;
            $all_years = $wpdb->get_col($wpdb->prepare(
                "SELECT DISTINCT meta_value FROM {$wpdb->postmeta} 
                WHERE meta_key = %s AND meta_value != ''",
                '_review_year'
            ));
            $year_name = '';
            foreach ($all_years as $year) {
                // Compare decoded versions - sanitize_title returns encoded, so decode it
                if (urldecode(sanitize_title($year)) === $year_slug) {
                    $year_name = $year;
                    break;
                }
            }
            if ($year_name) {
                if (!isset($args['meta_query'])) {
                    $args['meta_query'] = array();
                }
                $args['meta_query'][] = array(
                    'key' => '_review_year',
                    'value' => $year_name,
                    'compare' => '=',
                );
            }
        }
        
        if (!empty($tax_query)) {
            $args['tax_query'] = $tax_query;
            $args['tax_query']['relation'] = 'AND';
        }
        
        if (!empty($filters['has_video']) && $filters['has_video'] === 'true') {
            if (!isset($args['meta_query'])) {
                $args['meta_query'] = array();
            }
            $args['meta_query'][] = array(
                'key' => '_review_has_video',
                'value' => '1',
                'compare' => '=',
            );
        }
        
        $query = new WP_Query($args);
        
        ob_start();
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $this->render_review_item();
            }
        }
        
        $html = ob_get_clean();
        
        $count = $query->found_posts;
        
        wp_reset_postdata();
        
        wp_send_json_success(array(
            'html' => $html,
            'count' => $count,
            'has_more' => $query->max_num_pages > $page,
        ));
    }
    
    private function render_review_item() {
        $gallery_ids = get_post_meta(get_the_ID(), '_review_gallery', true);
        $gallery_ids = $gallery_ids ? explode(',', $gallery_ids) : array();
        $first_image_id = !empty($gallery_ids) ? $gallery_ids[0] : get_post_thumbnail_id();
        
        if (!$first_image_id) {
            return;
        }
        
        $image_url = wp_get_attachment_image_url($first_image_id, 'medium');
        $permalink = get_permalink();
        $title = get_the_title();
        ?>
        <a href="<?php echo esc_url($permalink); ?>" class="review-gallery-item">
            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>" loading="lazy" />
        </a>
        <?php
    }
}

