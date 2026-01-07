<?php
/**
 * Admin functionality for Reviews
 */

if (!defined('ABSPATH')) {
    exit;
}

class Reviews_Admin {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_boxes'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('admin_head', array($this, 'hide_unnecessary_elements'));
        // add_action('admin_menu', array($this, 'remove_admin_menu_items'), 999);
        add_action('admin_bar_menu', array($this, 'remove_admin_bar_items'), 999);
    }
    
    public function hide_unnecessary_elements() {
        global $post_type;
        if ($post_type === 'review') {
            ?>
            <style>
                #post-body-content .misc-pub-section,
                #post-body-content .misc-pub-post-status,
                #post-body-content .misc-pub-visibility,
                #post-body-content .misc-pub-curtime,
                #minor-publishing-actions,
                #post-status-info,
                .postbox:not(#review_content),
                #tagsdiv-review_city,
                #tagsdiv-review_product,
                #tagsdiv-review_year,
                #categorydiv,
                #tagsdiv {
                    display: none !important;
                }
                #titlediv {
                    margin-bottom: 20px;
                }
            </style>
            <?php
        }
    }
    
    public function add_meta_boxes() {
        add_meta_box(
            'review_content',
            'Содержание отзыва',
            array($this, 'render_review_content_meta_box'),
            'review',
            'normal',
            'high'
        );
        
        // Remove default WordPress meta boxes
        remove_meta_box('slugdiv', 'review', 'normal');
        remove_meta_box('postcustom', 'review', 'normal');
        remove_meta_box('commentsdiv', 'review', 'normal');
        remove_meta_box('commentstatusdiv', 'review', 'normal');
        remove_meta_box('trackbacksdiv', 'review', 'normal');
        remove_meta_box('authordiv', 'review', 'normal');
        remove_meta_box('revisionsdiv', 'review', 'normal');
    }
    
    public function render_review_content_meta_box($post) {
        wp_nonce_field('review_meta_box', 'review_meta_box_nonce');
        
        $reviewer_name = get_post_meta($post->ID, '_reviewer_name', true);
        $city = get_post_meta($post->ID, '_review_city', true);
        $year = get_post_meta($post->ID, '_review_year', true);
        // Set default year to current year if empty
        if (empty($year)) {
            $year = date('Y');
        }
        $product = get_post_meta($post->ID, '_review_product', true);
        $gallery_ids = get_post_meta($post->ID, '_review_gallery', true);
        $gallery_ids = $gallery_ids ? explode(',', $gallery_ids) : array();
        $video_url = get_post_meta($post->ID, '_review_video_url', true);
        $product_name = get_post_meta($post->ID, '_product_name', true);
        $product_price = get_post_meta($post->ID, '_product_price', true);
        $product_image_id = get_post_meta($post->ID, '_product_image_id', true);
        $product_url = get_post_meta($post->ID, '_product_url', true);
        
        ?>
        <table class="form-table">
            <tr>
                <th><label for="reviewer_name">Имя автора отзыва</label></th>
                <td>
                    <input type="text" id="reviewer_name" name="reviewer_name" value="<?php echo esc_attr($reviewer_name); ?>" class="regular-text" />
                </td>
            </tr>
            <tr>
                <th><label for="review_city">Город</label></th>
                <td>
                    <input type="text" id="review_city" name="review_city" value="<?php echo esc_attr($city); ?>" class="regular-text" />
                </td>
            </tr>
            <tr>
                <th><label for="review_year">Год</label></th>
                <td>
                    <input type="text" id="review_year" name="review_year" value="<?php echo esc_attr($year); ?>" class="regular-text" placeholder="<?php echo date('Y'); ?>" />
                    <p class="description">Укажите год отзыва (по умолчанию: текущий год)</p>
                </td>
            </tr>
        </table>
        
        <h3 style="margin-top: 20px;">Изделие</h3>
        <table class="form-table">
            <tr>
                <th><label for="review_product">Изделие</label></th>
                <td>
                    <input type="text" id="review_product" name="review_product" value="<?php echo esc_attr($product); ?>" class="regular-text" />
                    <p class="description">Автоматически заполняется из "Названия товара", если не указано</p>
                </td>
            </tr>
        </table>
        
        <h3 style="margin-top: 20px;">Галерея фотографий</h3>
        <div class="review-gallery-container">
            <input type="hidden" id="review_gallery" name="review_gallery" value="<?php echo esc_attr(implode(',', $gallery_ids)); ?>" />
            <div id="review_gallery_preview" class="review-gallery-preview">
                <?php
                foreach ($gallery_ids as $attachment_id) {
                    if ($attachment_id) {
                        $image = wp_get_attachment_image($attachment_id, 'thumbnail');
                        echo '<div class="gallery-item" data-id="' . esc_attr($attachment_id) . '">';
                        echo $image;
                        echo '<button type="button" class="remove-image">×</button>';
                        echo '</div>';
                    }
                }
                ?>
            </div>
            <button type="button" class="button button-primary" id="add_gallery_images">Добавить фотографии</button>
            <p class="description">Первая фотография будет использована как основное изображение в галерее</p>
        </div>
        
        <h3 style="margin-top: 20px;">Видео отзыва</h3>
        <table class="form-table">
            <tr>
                <th><label for="review_video_url">URL видео</label></th>
                <td>
                    <input type="url" id="review_video_url" name="review_video_url" value="<?php echo esc_url($video_url); ?>" class="regular-text" />
                    <p class="description">Вставьте ссылку на видео (YouTube, Vimeo и т.д.)</p>
                </td>
            </tr>
        </table>
        
        <h3 style="margin-top: 20px;">Информация о товаре</h3>
        <table class="form-table">
            <tr>
                <th><label for="product_name">Название товара</label></th>
                <td>
                    <input type="text" id="product_name" name="product_name" value="<?php echo esc_attr($product_name); ?>" class="regular-text" />
                    <p class="description">Название товара будет использовано в URL страницы отзыва</p>
                </td>
            </tr>
            <tr>
                <th><label for="product_price">Цена товара</label></th>
                <td>
                    <input type="text" id="product_price" name="product_price" value="<?php echo esc_attr($product_price); ?>" class="regular-text" placeholder="54 000 Р" />
                    <p class="description">Укажите цену с валютой (например: 54 000 Р)</p>
                </td>
            </tr>
            <tr>
                <th><label for="product_image_id">Фото товара</label></th>
                <td>
                    <input type="hidden" id="product_image_id" name="product_image_id" value="<?php echo esc_attr($product_image_id); ?>" />
                    <div id="product_image_preview" style="margin-bottom: 10px;">
                        <?php
                        if ($product_image_id) {
                            echo wp_get_attachment_image($product_image_id, 'thumbnail');
                        }
                        ?>
                    </div>
                    <button type="button" class="button" id="select_product_image">Выбрать изображение</button>
                    <button type="button" class="button" id="remove_product_image" style="<?php echo $product_image_id ? '' : 'display:none;'; ?>">Удалить изображение</button>
                </td>
            </tr>
            <tr>
                <th><label for="product_url">Ссылка на товар</label></th>
                <td>
                    <input type="url" id="product_url" name="product_url" value="<?php echo esc_url($product_url); ?>" class="regular-text" />
                    <p class="description">Ссылка на страницу товара в магазине</p>
                </td>
            </tr>
        </table>
        <?php
    }
    
    
    public function save_meta_boxes($post_id) {
        // Check nonce
        if (!isset($_POST['review_meta_box_nonce']) || !wp_verify_nonce($_POST['review_meta_box_nonce'], 'review_meta_box')) {
            return;
        }
        
        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Save meta fields
        if (isset($_POST['reviewer_name'])) {
            update_post_meta($post_id, '_reviewer_name', sanitize_text_field($_POST['reviewer_name']));
        }
        
        if (isset($_POST['review_city'])) {
            update_post_meta($post_id, '_review_city', sanitize_text_field($_POST['review_city']));
        }
        
        if (isset($_POST['review_year'])) {
            $year = sanitize_text_field($_POST['review_year']);
            // Set default year to current year if empty
            if (empty($year)) {
                $year = date('Y');
            }
            update_post_meta($post_id, '_review_year', $year);
        }
        
        if (isset($_POST['review_gallery'])) {
            update_post_meta($post_id, '_review_gallery', sanitize_text_field($_POST['review_gallery']));
        }
        
        if (isset($_POST['review_video_url'])) {
            update_post_meta($post_id, '_review_video_url', esc_url_raw($_POST['review_video_url']));
        }
        
        // Save product fields
        if (isset($_POST['product_name'])) {
            update_post_meta($post_id, '_product_name', sanitize_text_field($_POST['product_name']));
        }
        
        // Save product (изделие) - auto-fill from product_name if empty
        if (isset($_POST['review_product'])) {
            $product = sanitize_text_field($_POST['review_product']);
            // If product is empty, use product_name
            if (empty($product) && isset($_POST['product_name']) && !empty($_POST['product_name'])) {
                $product = sanitize_text_field($_POST['product_name']);
            }
            update_post_meta($post_id, '_review_product', $product);
        } elseif (isset($_POST['product_name']) && !empty($_POST['product_name'])) {
            // If review_product not set but product_name is, use product_name
            update_post_meta($post_id, '_review_product', sanitize_text_field($_POST['product_name']));
        }
        
        if (isset($_POST['product_price'])) {
            update_post_meta($post_id, '_product_price', sanitize_text_field($_POST['product_price']));
        }
        
        if (isset($_POST['product_image_id'])) {
            update_post_meta($post_id, '_product_image_id', intval($_POST['product_image_id']));
        }
        
        if (isset($_POST['product_url'])) {
            update_post_meta($post_id, '_product_url', esc_url_raw($_POST['product_url']));
        }
    }
    
    public function enqueue_admin_scripts($hook) {
        if ('post.php' !== $hook && 'post-new.php' !== $hook) {
            return;
        }
        
        global $post_type;
        if ('review' !== $post_type) {
            return;
        }
        
        wp_enqueue_media();
        wp_enqueue_script(
            'reviews-admin-script',
            REVIEWS_PLUGIN_URL . 'assets/js/reviews-admin.js',
            array('jquery'),
            REVIEWS_PLUGIN_VERSION,
            true
        );
        
        wp_enqueue_style(
            'reviews-admin-style',
            REVIEWS_PLUGIN_URL . 'assets/css/reviews-admin.css',
            array(),
            REVIEWS_PLUGIN_VERSION
        );
    }
    
    /**
     * Remove unnecessary admin menu items
     */
    public function remove_admin_menu_items() {
        // Remove default WordPress menu items
        // remove_menu_page('index.php'); // Dashboard
        // remove_menu_page('edit.php'); // Posts
        // remove_menu_page('upload.php'); // Media
        // remove_menu_page('edit.php?post_type=page'); // Pages
        // remove_menu_page('edit-comments.php'); // Comments
        // remove_menu_page('themes.php'); // Appearance
        // remove_menu_page('plugins.php'); // Plugins
        // remove_menu_page('users.php'); // Users
        // remove_menu_page('tools.php'); // Tools
        // remove_menu_page('options-general.php'); // Settings
        
        // Remove submenu items
        // remove_submenu_page('index.php', 'update-core.php'); // Updates
    }
    
    /**
     * Remove unnecessary admin bar items
     */
    public function remove_admin_bar_items($wp_admin_bar) {
        $wp_admin_bar->remove_node('wp-logo');
        $wp_admin_bar->remove_node('site-name');
        $wp_admin_bar->remove_node('new-content');
        $wp_admin_bar->remove_node('comments');
        $wp_admin_bar->remove_node('updates');
        $wp_admin_bar->remove_node('search');
    }
}

