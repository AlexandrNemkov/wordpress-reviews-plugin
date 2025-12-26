<?php
/**
 * Register Custom Post Type for Reviews
 */

if (!defined('ABSPATH')) {
    exit;
}

class Reviews_Post_Type {
    
    private static $instance = null;
    const POST_TYPE = 'review';
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('init', array($this, 'register_post_type'));
        add_action('init', array($this, 'register_taxonomies'));
        add_action('init', array($this, 'add_rewrite_rules'));
        add_filter('post_type_link', array($this, 'custom_review_permalink'), 10, 2);
        add_filter('request', array($this, 'parse_review_request'));
    }
    
    public function register_post_type() {
        $labels = array(
            'name'                  => 'Отзывы',
            'singular_name'         => 'Отзыв',
            'menu_name'             => 'Отзывы',
            'name_admin_bar'        => 'Отзыв',
            'archives'              => 'Архив отзывов',
            'attributes'            => 'Атрибуты отзыва',
            'parent_item_colon'     => 'Родительский отзыв:',
            'all_items'             => 'Все отзывы',
            'add_new_item'          => 'Добавить новый отзыв',
            'add_new'               => 'Добавить новый',
            'new_item'              => 'Новый отзыв',
            'edit_item'             => 'Редактировать отзыв',
            'update_item'           => 'Обновить отзыв',
            'view_item'             => 'Просмотреть отзыв',
            'view_items'            => 'Просмотреть отзывы',
            'search_items'          => 'Искать отзывы',
            'not_found'             => 'Не найдено',
            'not_found_in_trash'    => 'Не найдено в корзине',
        );
        
        $args = array(
            'label'                 => 'Отзыв',
            'description'           => 'Отзывы клиентов',
            'labels'                => $labels,
            'supports'              => array('title', 'editor', 'thumbnail'),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 20,
            'menu_icon'             => 'dashicons-star-filled',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
            'rewrite'               => array('slug' => 'reviews', 'with_front' => false),
        );
        
        register_post_type(self::POST_TYPE, $args);
    }
    
    public function register_taxonomies() {
        // Город
        register_taxonomy('review_city', array(self::POST_TYPE), array(
            'labels' => array(
                'name' => 'Города',
                'singular_name' => 'Город',
                'search_items' => 'Искать города',
                'all_items' => 'Все города',
                'edit_item' => 'Редактировать город',
                'update_item' => 'Обновить город',
                'add_new_item' => 'Добавить новый город',
                'new_item_name' => 'Название города',
                'menu_name' => 'Города',
            ),
            'hierarchical' => false,
            'public' => true,
            'show_ui' => false,
            'show_admin_column' => false,
            'query_var' => true,
            'rewrite' => array('slug' => 'review-city'),
        ));
        
        // Изделие
        register_taxonomy('review_product', array(self::POST_TYPE), array(
            'labels' => array(
                'name' => 'Изделия',
                'singular_name' => 'Изделие',
                'search_items' => 'Искать изделия',
                'all_items' => 'Все изделия',
                'edit_item' => 'Редактировать изделие',
                'update_item' => 'Обновить изделие',
                'add_new_item' => 'Добавить новое изделие',
                'new_item_name' => 'Название изделия',
                'menu_name' => 'Изделия',
            ),
            'hierarchical' => false,
            'public' => true,
            'show_ui' => false,
            'show_admin_column' => false,
            'query_var' => true,
            'rewrite' => array('slug' => 'review-product'),
        ));
        
        // Год
        register_taxonomy('review_year', array(self::POST_TYPE), array(
            'labels' => array(
                'name' => 'Годы',
                'singular_name' => 'Год',
                'search_items' => 'Искать годы',
                'all_items' => 'Все годы',
                'edit_item' => 'Редактировать год',
                'update_item' => 'Обновить год',
                'add_new_item' => 'Добавить новый год',
                'new_item_name' => 'Название года',
                'menu_name' => 'Годы',
            ),
            'hierarchical' => false,
            'public' => true,
            'show_ui' => false,
            'show_admin_column' => false,
            'query_var' => true,
            'rewrite' => array('slug' => 'review-year'),
        ));
    }
    
    /**
     * Transliterate Cyrillic to Latin
     */
    private function transliterate($text) {
        $cyr = array(
            'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п',
            'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я',
            'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П',
            'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я'
        );
        $lat = array(
            'a', 'b', 'v', 'g', 'd', 'e', 'yo', 'zh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p',
            'r', 's', 't', 'u', 'f', 'h', 'ts', 'ch', 'sh', 'sch', '', 'y', '', 'e', 'yu', 'ya',
            'A', 'B', 'V', 'G', 'D', 'E', 'Yo', 'Zh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P',
            'R', 'S', 'T', 'U', 'F', 'H', 'Ts', 'Ch', 'Sh', 'Sch', '', 'Y', '', 'E', 'Yu', 'Ya'
        );
        return str_replace($cyr, $lat, $text);
    }
    
    /**
     * Sanitize slug - remove special characters, convert to lowercase
     */
    private function sanitize_slug($text) {
        // Convert to lowercase
        $text = mb_strtolower($text, 'UTF-8');
        // Replace spaces and special characters with hyphens
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        // Remove leading/trailing hyphens
        $text = trim($text, '-');
        return $text;
    }
    
    /**
     * Add rewrite rules for product name based URLs
     */
    public function add_rewrite_rules() {
        add_rewrite_rule(
            '^reviews/([^/]+)/?$',
            'index.php?post_type=review&product_slug=$matches[1]',
            'top'
        );
        add_rewrite_tag('%product_slug%', '([^&]+)');
    }
    
    /**
     * Custom permalink for reviews using product name
     */
    public function custom_review_permalink($post_link, $post) {
        if ($post->post_type !== self::POST_TYPE) {
            return $post_link;
        }
        
        $product_name = get_post_meta($post->ID, '_product_name', true);
        
        if ($product_name) {
            // Transliterate to Latin first
            $transliterated = $this->transliterate($product_name);
            // Remove any remaining non-ASCII characters and sanitize
            $product_slug = $this->sanitize_slug($transliterated);
            
            // Build new permalink
            $post_link = home_url('/reviews/' . $product_slug . '/');
        }
        
        return $post_link;
    }
    
    /**
     * Parse request to find review by product slug
     */
    public function parse_review_request($query_vars) {
        if (isset($query_vars['product_slug']) && !empty($query_vars['product_slug'])) {
            $product_slug = urldecode($query_vars['product_slug']);
            
            // Get all reviews to check product names
            $args = array(
                'post_type' => self::POST_TYPE,
                'posts_per_page' => -1,
                'post_status' => 'publish'
            );
            
            $posts = get_posts($args);
            
            if (!empty($posts)) {
                // Check if product name matches slug (with transliteration)
                foreach ($posts as $post) {
                    $product_name = get_post_meta($post->ID, '_product_name', true);
                    if ($product_name) {
                        // Transliterate and sanitize
                        $transliterated = $this->transliterate($product_name);
                        $sanitized_slug = $this->sanitize_slug($transliterated);
                        if ($sanitized_slug === $product_slug) {
                            $query_vars['post_type'] = self::POST_TYPE;
                            $query_vars['name'] = $post->post_name;
                            unset($query_vars['product_slug']);
                            break;
                        }
                    }
                }
            }
        }
        
        return $query_vars;
    }
}

